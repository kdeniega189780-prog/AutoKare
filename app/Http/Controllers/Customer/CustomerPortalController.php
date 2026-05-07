<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerPortalController extends Controller
{
    /** ----------------------------------------------------------------
     * Helpers
     * ---------------------------------------------------------------- */
    private function ownedVehicleIds(int $userId): array
    {
        return Vehicle::query()
            ->where('owner_id', $userId)
            ->orWhere('user_id', $userId)
            ->pluck('id')
            ->all();
    }

    /** ----------------------------------------------------------------
     * My Vehicles
     * ---------------------------------------------------------------- */
    public function vehicles(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->isOwner(), 403);

        $vehicles = Vehicle::query()
            ->where('owner_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->with(['maintenanceSchedules' => fn ($q) => $q->orderByDesc('scheduled_at')])
            ->get()
            ->map(function (Vehicle $v) {
                $completed = $v->maintenanceSchedules->where('status', 'completed')->sortByDesc('scheduled_at')->first();
                $upcoming = $v->maintenanceSchedules->where('status', 'pending')->sortBy('scheduled_at')->first();
                $hasActive = $v->maintenanceSchedules
                    ->whereIn('status', ['pending', 'in_progress'])
                    ->isNotEmpty();

                $isOverdue = $upcoming && $upcoming->scheduled_at?->isPast();

                return [
                    'id' => $v->id,
                    'title' => $v->displayLabel(),
                    'plate' => $v->license_plate,
                    'last_service' => $completed?->scheduled_at?->format('Y-m-d'),
                    'next_service' => $upcoming?->scheduled_at?->format('Y-m-d'),
                    'status' => $isOverdue ? 'service_due' : 'good',
                    'overdue' => $isOverdue,
                    'overdue_date' => $upcoming?->scheduled_at?->format('Y-m-d'),
                    'has_active_appointment' => $hasActive,
                ];
            })
            ->values();

        return view('customer.vehicles', compact('vehicles'));
    }

    public function vehicleAddModal(): View
    {
        return view('modals.customer.vehicles.add');
    }

    public function vehicleViewModal(Vehicle $vehicle, Request $request): View
    {
        $user = $request->user();
        abort_unless(in_array($vehicle->id, $this->ownedVehicleIds($user->id), true), 403);

        return view('modals.customer.vehicles.view', compact('vehicle'));
    }

    public function vehicleScheduleModal(Vehicle $vehicle, Request $request): View
    {
        $user = $request->user();
        abort_unless(in_array($vehicle->id, $this->ownedVehicleIds($user->id), true), 403);

        $vehicles = Vehicle::query()
            ->where('owner_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->orderBy('make')
            ->get();

        return view('modals.customer.appointments.book', compact('vehicles', 'vehicle'));
    }

    public function vehicleStore(Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isOwner(), 403);

        $validated = $request->validate([
            'make' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1900', 'max:'.(date('Y') + 1)],
            'license_plate' => ['required', 'string', 'max:32'],
            'mileage' => ['nullable', 'integer', 'min:0'],
        ]);

        Vehicle::create([
            'owner_id' => $user->id,
            'user_id' => $user->id,
            'make' => $validated['make'],
            'model' => $validated['model'],
            'year' => $validated['year'],
            'license_plate' => $validated['license_plate'],
            'mileage' => $validated['mileage'] ?? null,
            'status' => 'active',
        ]);

        return redirect()->route('customer.vehicles')->with('status', 'Vehicle added.');
    }

    /** ----------------------------------------------------------------
     * Appointments
     * ---------------------------------------------------------------- */
    public function appointments(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->isOwner(), 403);

        $vehicleIds = $this->ownedVehicleIds($user->id);

        $appointments = MaintenanceSchedule::with('vehicle')
            ->whereIn('vehicle_id', $vehicleIds)
            ->whereIn('status', ['pending', 'in_progress'])
            ->orderByDesc('scheduled_at')
            ->get();

        $vehicles = Vehicle::query()
            ->where('owner_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->orderBy('make')
            ->get();

        return view('customer.appointments', compact('appointments', 'vehicles'));
    }

    public function appointmentBookModal(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->isOwner(), 403);

        $vehicles = Vehicle::query()
            ->where('owner_id', $user->id)
            ->orWhere('user_id', $user->id)
            ->orderBy('make')
            ->get();

        return view('modals.customer.appointments.book', ['vehicles' => $vehicles, 'vehicle' => null]);
    }

    public function appointmentCancel(MaintenanceSchedule $schedule, Request $request): RedirectResponse
    {
        $user = $request->user();
        abort_unless($user && $user->isOwner(), 403);
        abort_unless(in_array($schedule->vehicle_id, $this->ownedVehicleIds($user->id), true), 403);

        $schedule->update(['status' => 'cancelled']);

        return redirect()->route('customer.appointments')->with('status', 'Appointment cancelled.');
    }

    /** ----------------------------------------------------------------
     * Service History
     * ---------------------------------------------------------------- */
    public function serviceHistory(Request $request): View
    {
        $user = $request->user();
        abort_unless($user && $user->isOwner(), 403);

        $vehicleIds = $this->ownedVehicleIds($user->id);

        $records = MaintenanceSchedule::with(['vehicle', 'mechanic', 'serviceRecord'])
            ->whereIn('vehicle_id', $vehicleIds)
            ->where('status', 'completed')
            ->orderByDesc('scheduled_at')
            ->get();

        return view('customer.service-history', compact('records'));
    }
}
