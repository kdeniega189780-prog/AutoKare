<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminMaintenanceController extends Controller
{
    public function index(Request $request): View
    {
        $vehicleId = $request->query('vehicle_id');
        $status = $request->query('status');
        $date = $request->query('date');

        $query = MaintenanceSchedule::query()
            ->with(['vehicle', 'mechanic', 'serviceRecord'])
            ->latest('scheduled_at');

        if ($vehicleId) {
            $query->where('vehicle_id', $vehicleId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->whereDate('scheduled_at', $date);
        }

        $records = $query->paginate(15)->appends([
            'vehicle_id' => $vehicleId,
            'status' => $status,
            'date' => $date,
        ]);

        $vehicles = Vehicle::query()->orderBy('make')->orderBy('model')->get();

        return view('admin.maintenance.index', compact('records', 'vehicles', 'vehicleId', 'status', 'date'));
    }

    public function viewModal(MaintenanceSchedule $schedule): View
    {
        $schedule->load(['vehicle', 'mechanic', 'serviceRecord']);

        return view('modals.admin.maintenance.view', compact('schedule'));
    }

    public function editModal(Request $request, MaintenanceSchedule $schedule): View
    {
        $schedule->load(['vehicle', 'mechanic', 'serviceRecord']);

        $vehicles = Vehicle::query()->orderBy('make')->orderBy('model')->get();
        $mechanics = User::query()->where('role', 'mechanic')->orderBy('name')->get();
        $record = $schedule->serviceRecord ?: new ServiceRecord(['maintenance_schedule_id' => $schedule->id]);

        return view('modals.admin.maintenance.edit', compact('schedule', 'vehicles', 'mechanics', 'record'));
    }

    public function update(Request $request, MaintenanceSchedule $schedule): RedirectResponse
    {
        $validated = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'mechanic_id' => ['nullable', 'exists:users,id'],
            'scheduled_at' => ['required', 'date'],
            'task_description' => ['required', 'string', 'max:500'],
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
            'parts_used' => ['nullable', 'string', 'max:5000'],
            'total_cost' => ['nullable', 'numeric', 'min:0'],
            'labor_cost' => ['nullable', 'numeric', 'min:0'],
            'parts_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $schedule->update([
            'vehicle_id' => $validated['vehicle_id'],
            'mechanic_id' => $validated['mechanic_id'] ?? null,
            'scheduled_at' => $validated['scheduled_at'],
            'task_description' => $validated['task_description'],
            'status' => $validated['status'],
        ]);

        $record = ServiceRecord::firstOrNew(['maintenance_schedule_id' => $schedule->id]);
        $totalCost = isset($validated['total_cost']) ? (float) $validated['total_cost'] : null;
        $labor = array_key_exists('labor_cost', $validated) ? $validated['labor_cost'] : null;
        $parts = array_key_exists('parts_cost', $validated) ? $validated['parts_cost'] : null;

        // UI uses a single "Cost" field for pixel-close matching.
        // If total_cost is provided, store it as labor_cost with parts_cost=0 to preserve totals.
        if ($totalCost !== null) {
            $labor = $totalCost;
            $parts = 0;
        }
        $record->fill([
            'parts_used' => $validated['parts_used'] ?? null,
            'labor_cost' => $labor,
            'parts_cost' => $parts,
            'notes' => $validated['notes'] ?? null,
        ]);
        $record->save();

        return redirect()->route('admin.maintenance.index')->with('status', 'Maintenance record updated.');
    }
}

