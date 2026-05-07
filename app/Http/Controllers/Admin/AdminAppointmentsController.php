<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAppointmentsController extends Controller
{
    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $query = MaintenanceSchedule::query()
            ->with(['vehicle.owner'])
            ->latest('scheduled_at');

        if ($q !== '') {
            $query->where(function ($sub) use ($q) {
                $sub->where('task_description', 'like', "%{$q}%")
                    ->orWhereHas('vehicle', function ($v) use ($q) {
                        $v->where('make', 'like', "%{$q}%")
                            ->orWhere('model', 'like', "%{$q}%")
                            ->orWhere('license_plate', 'like', "%{$q}%");
                    })
                    ->orWhereHas('vehicle.owner', function ($u) use ($q) {
                        $u->where('name', 'like', "%{$q}%")
                            ->orWhere('phone', 'like', "%{$q}%");
                    });
            });
        }

        $schedules = $query->paginate(15)->appends(['q' => $q]);

        return view('admin.appointments.index', compact('schedules', 'q'));
    }

    public function createModal(Request $request): View
    {
        $this->authorize('create', MaintenanceSchedule::class);

        $mechanics = User::query()
            ->where('role', 'mechanic')
            ->orderBy('name')
            ->get();

        $vehicles = Vehicle::query()
            ->with('owner')
            ->orderBy('make')
            ->orderBy('model')
            ->limit(200)
            ->get();

        return view('modals.admin.appointments.create', compact('vehicles', 'mechanics'));
    }

    public function viewModal(MaintenanceSchedule $schedule): View
    {
        $this->authorize('view', $schedule);
        $schedule->load(['vehicle.owner', 'mechanic']);

        return view('modals.admin.appointments.view', compact('schedule'));
    }

    public function editModal(Request $request, MaintenanceSchedule $schedule): View
    {
        $this->authorize('update', $schedule);
        $schedule->load(['vehicle.owner', 'mechanic']);

        $mechanics = User::query()
            ->where('role', 'mechanic')
            ->orderBy('name')
            ->get();

        $vehicles = Vehicle::query()
            ->with('owner')
            ->orderBy('make')
            ->orderBy('model')
            ->limit(200)
            ->get();

        return view('modals.admin.appointments.edit', compact('schedule', 'vehicles', 'mechanics'));
    }

    public function suggest(Request $request): JsonResponse
    {
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
            'primary' => $v->license_plate,
            'secondary' => trim("{$v->make} {$v->model}"),
            'meta' => $v->owner?->name,
        ]));
    }
}

