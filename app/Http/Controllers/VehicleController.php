<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Vehicle;
use App\Http\Requests\StoreVehicleRequest;
use App\Http\Requests\UpdateVehicleRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VehicleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Vehicle::class);

        $user = $request->user();
        $query = Vehicle::query()->with('owner')->latest();
        $q = trim((string) $request->query('q', ''));

        if ($user->isOwner()) {
            $query->where('owner_id', $user->id)->orWhere('user_id', $user->id);
        } elseif ($user->isMechanic()) {
            $query->whereHas('maintenanceSchedules', fn ($q) => $q->where('assigned_mechanic_id', $user->id)->orWhere('mechanic_id', $user->id));
        }

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('license_plate', 'like', "%{$q}%")
                    ->orWhere('make', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%")
                    ->orWhere('type', 'like', "%{$q}%")
                    ->orWhere('color', 'like', "%{$q}%");

                if (ctype_digit($q)) {
                    $sub->orWhere('id', (int) $q);
                }

                $sub->orWhereHas('owner', function ($owner) use ($q) {
                    $owner->where('name', 'like', "%{$q}%")
                        ->orWhere('email', 'like', "%{$q}%")
                        ->orWhere('phone', 'like', "%{$q}%");
                });
            });
        }

        $vehicles = $query->paginate(12);

        return view('vehicles.index', compact('vehicles', 'q'));
    }

    public function createModal(Request $request): View
    {
        $view = $this->create($request);
        return view('modals.vehicles.create', $view->getData());
    }

    public function create(Request $request): View
    {
        $this->authorize('create', Vehicle::class);

        $owners = $request->user()->isAdmin()
            ? User::where('role', 'owner')->orderBy('name')->get()
            : collect();

        return view('vehicles.create', compact('owners'));
    }

    public function store(StoreVehicleRequest $request): RedirectResponse
    {
        $this->authorize('create', Vehicle::class);
        $validated = $request->validated();

        $userId = $request->user()->isAdmin()
            ? (int) $validated['user_id']
            : $request->user()->id;

        unset($validated['user_id']);

        Vehicle::create([
            ...$validated,
            'user_id' => $userId,
            'owner_id' => $userId,
        ]);

        return redirect()->route('vehicles.index')->with('status', 'Vehicle saved.');
    }

    public function show(Vehicle $vehicle): View
    {
        $this->authorize('view', $vehicle);
        $vehicle->load(['owner', 'maintenanceSchedules.mechanic', 'maintenanceSchedules.serviceRecord']);

        return view('vehicles.show', compact('vehicle'));
    }

    public function showModal(Vehicle $vehicle): View
    {
        $view = $this->show($vehicle);
        return view('modals.vehicles.show', $view->getData());
    }

    public function edit(Request $request, Vehicle $vehicle): View
    {
        $this->authorize('update', $vehicle);

        $owners = $request->user()->isAdmin()
            ? User::where('role', 'owner')->orderBy('name')->get()
            : collect();

        return view('vehicles.edit', compact('vehicle', 'owners'));
    }

    public function editModal(Request $request, Vehicle $vehicle): View
    {
        $view = $this->edit($request, $vehicle);
        return view('modals.vehicles.edit', $view->getData());
    }

    public function update(UpdateVehicleRequest $request, Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('update', $vehicle);
        $validated = $request->validated();

        if ($request->user()->isAdmin()) {
            $validated['user_id'] = (int) $validated['user_id'];
        }

        if (array_key_exists('user_id', $validated)) {
            $validated['owner_id'] = $validated['user_id'];
        }

        $vehicle->update($validated);

        return redirect()->route('vehicles.show', $vehicle)->with('status', 'Vehicle updated.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        $this->authorize('delete', $vehicle);
        $vehicle->delete();

        return redirect()->route('vehicles.index')->with('status', 'Vehicle removed.');
    }

    public function suggest(Request $request): JsonResponse
    {
        $this->authorize('viewAny', Vehicle::class);

        $q = trim((string) $request->query('q', ''));

        if (mb_strlen($q) < 1) {
            return response()->json([]);
        }

        $vehicles = Vehicle::query()
            ->with('owner:id,name')
            ->where(function ($sub) use ($q) {
                $sub->where('license_plate', 'like', "%{$q}%")
                    ->orWhere('make', 'like', "%{$q}%")
                    ->orWhere('model', 'like', "%{$q}%")
                    ->orWhereHas('owner', fn ($u) => $u->where('name', 'like', "%{$q}%"));
            })
            ->orderBy('license_plate')
            ->limit(10)
            ->get(['id', 'license_plate', 'make', 'model', 'owner_id']);

        return response()->json($vehicles->map(fn ($v) => [
            'value' => $v->license_plate,
            'primary' => "{$v->license_plate} - {$v->make} {$v->model}",
            'secondary' => $v->owner?->name,
            'meta' => null,
        ]));
    }
}
