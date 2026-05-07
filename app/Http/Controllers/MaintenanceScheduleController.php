<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreScheduleRequest;
use App\Http\Requests\UpdateScheduleRequest;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MaintenanceScheduleController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', MaintenanceSchedule::class);

        $user = $request->user();
        $query = MaintenanceSchedule::query()
            ->with(['vehicle.owner', 'mechanic'])
            ->latest('scheduled_at');

        if ($user->isOwner()) {
            $query->whereHas('vehicle', fn ($q) => $q->where('owner_id', $user->id)->orWhere('user_id', $user->id));
        } elseif ($user->isMechanic()) {
            $query->where(fn ($q) => $q->where('assigned_mechanic_id', $user->id)->orWhere('mechanic_id', $user->id));
        }

        $schedules = $query->paginate(15);

        return view('schedules.index', compact('schedules'));
    }

    public function createModal(Request $request): View
    {
        $view = $this->create($request);
        return view('modals.schedules.create', $view->getData());
    }

    public function create(Request $request): View
    {
        $this->authorize('create', MaintenanceSchedule::class);

        $vehicles = Vehicle::query()
            ->when($request->user()->isOwner(), fn ($q) => $q->where('owner_id', $request->user()->id)->orWhere('user_id', $request->user()->id))
            ->orderBy('make')
            ->get();

        $mechanics = User::where('role', 'mechanic')->orderBy('name')->get();

        $selectedVehicleId = old('vehicle_id', $request->query('vehicle_id'));

        return view('schedules.create', compact('vehicles', 'mechanics', 'selectedVehicleId'));
    }

    public function store(StoreScheduleRequest $request): RedirectResponse
    {
        $this->authorize('create', MaintenanceSchedule::class);

        // Combine scheduled_date + scheduled_time into scheduled_at if needed.
        if (! $request->filled('scheduled_at') && $request->filled('scheduled_date')) {
            $time = $request->input('scheduled_time') ?: '09:00';
            $request->merge([
                'scheduled_at' => $request->input('scheduled_date') . ' ' . $time,
            ]);
        }

        $validated = $request->validated();

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($request->user()->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) !== $request->user()->id)) {
            abort(403);
        }

        if (! empty($validated['mechanic_id'])) {
            $mechanic = User::find($validated['mechanic_id']);
            if (! $mechanic || ! $mechanic->isMechanic()) {
                return back()->withErrors(['mechanic_id' => 'Select a valid mechanic.'])->withInput();
            }
        }

        MaintenanceSchedule::create([
            'vehicle_id' => $validated['vehicle_id'],
            'mechanic_id' => $validated['mechanic_id'] ?? null,
            'assigned_mechanic_id' => $validated['mechanic_id'] ?? null,
            'created_by' => $request->user()->id,
            'scheduled_at' => $validated['scheduled_at'],
            'scheduled_date' => isset($validated['scheduled_at']) ? date('Y-m-d', strtotime((string) $validated['scheduled_at'])) : null,
            'task_description' => $validated['task_description'],
            'description' => $validated['description'] ?? $validated['task_description'],
            'status' => $validated['status'] ?? 'pending',
            'priority' => $validated['priority'] ?? 'normal',
            'payment_method' => $validated['payment_method'] ?? null,
            'task_type' => $this->guessTaskType($validated['task_description']),
        ]);

        $redirect = match (true) {
            $request->user()->isAdmin() => 'admin.appointments.index',
            $request->user()->isOwner() => 'customer.appointments',
            default => 'schedules.index',
        };

        return redirect()->route($redirect)->with('status', 'Appointment booked.');
    }

    private function guessTaskType(string $desc): string
    {
        $d = strtolower($desc);
        return match (true) {
            str_contains($d, 'oil') => 'oil_change',
            str_contains($d, 'tire') => 'tire_rotation',
            str_contains($d, 'brake') => 'brake',
            str_contains($d, 'air') => 'air_filter',
            str_contains($d, 'engine') => 'engine_diagnostic',
            str_contains($d, 'transmission') => 'transmission',
            default => 'general',
        };
    }

    public function show(MaintenanceSchedule $schedule): View
    {
        $this->authorize('view', $schedule);
        $schedule->load(['vehicle.owner', 'mechanic', 'serviceRecord']);

        return view('schedules.show', compact('schedule'));
    }

    public function showModal(MaintenanceSchedule $schedule): View
    {
        $view = $this->show($schedule);
        return view('modals.schedules.show', $view->getData());
    }

    public function startModal(MaintenanceSchedule $schedule): View
    {
        $view = $this->show($schedule);
        return view('modals.mechanic.start-task', $view->getData());
    }

    public function assignModal(MaintenanceSchedule $schedule): View
    {
        $view = $this->show($schedule);
        return view('modals.mechanic.assign-task', $view->getData());
    }

    public function edit(Request $request, MaintenanceSchedule $schedule): View
    {
        $this->authorize('update', $schedule);

        $vehicles = Vehicle::query()
            ->when($request->user()->isOwner(), fn ($q) => $q->where('owner_id', $request->user()->id)->orWhere('user_id', $request->user()->id))
            ->orderBy('make')
            ->get();

        $mechanics = User::where('role', 'mechanic')->orderBy('name')->get();

        return view('schedules.edit', compact('schedule', 'vehicles', 'mechanics'));
    }

    public function editModal(Request $request, MaintenanceSchedule $schedule): View
    {
        $view = $this->edit($request, $schedule);
        return view('modals.schedules.edit', $view->getData());
    }

    public function update(UpdateScheduleRequest $request, MaintenanceSchedule $schedule): RedirectResponse
    {
        $this->authorize('update', $schedule);

        if (! $request->filled('scheduled_at') && $request->filled('scheduled_date')) {
            $time = $request->input('scheduled_time') ?: '09:00';
            $request->merge([
                'scheduled_at' => $request->input('scheduled_date') . ' ' . $time,
            ]);
        }

        $validated = $request->validated();

        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        if ($request->user()->isOwner() && (($vehicle->owner_id ?? $vehicle->user_id) !== $request->user()->id)) {
            abort(403);
        }

        if (! empty($validated['mechanic_id'])) {
            $mechanic = User::find($validated['mechanic_id']);
            if (! $mechanic || ! $mechanic->isMechanic()) {
                return back()->withErrors(['mechanic_id' => 'Select a valid mechanic.'])->withInput();
            }
        }

        $schedule->update([
            'vehicle_id' => $validated['vehicle_id'],
            'mechanic_id' => $validated['mechanic_id'] ?? null,
            'assigned_mechanic_id' => $validated['mechanic_id'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'scheduled_date' => isset($validated['scheduled_at']) ? date('Y-m-d', strtotime((string) $validated['scheduled_at'])) : ($schedule->scheduled_date ?? null),
            'task_description' => $validated['task_description'],
            'description' => $validated['task_description'],
            'status' => $validated['status'],
            'priority' => $validated['priority'] ?? $schedule->priority ?? 'normal',
        ]);

        return redirect()->route('schedules.show', $schedule)->with('status', 'Schedule updated.');
    }

    public function destroy(MaintenanceSchedule $schedule): RedirectResponse
    {
        $this->authorize('delete', $schedule);
        $schedule->delete();

        return redirect()->route('schedules.index')->with('status', 'Schedule removed.');
    }
}
