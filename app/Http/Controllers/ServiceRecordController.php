<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\ServiceRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ServiceRecordController extends Controller
{
    public function edit(MaintenanceSchedule $schedule): View
    {
        $this->authorize('fillServiceRecord', $schedule);

        $schedule->load('vehicle.owner');
        $record = ServiceRecord::firstOrNew(['maintenance_schedule_id' => $schedule->id]);

        return view('service-records.edit', compact('schedule', 'record'));
    }

    public function editModal(MaintenanceSchedule $schedule): View
    {
        $view = $this->edit($schedule);
        return view('modals.service-records.edit', $view->getData());
    }

    public function update(Request $request, MaintenanceSchedule $schedule): RedirectResponse
    {
        $this->authorize('fillServiceRecord', $schedule);

        $validated = $request->validate([
            'parts_used' => ['nullable', 'string', 'max:5000'],
            'labor_hours' => ['nullable', 'numeric', 'min:0'],
            'labor_cost' => ['nullable', 'numeric', 'min:0'],
            'parts_cost' => ['nullable', 'numeric', 'min:0'],
            'notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $markCompleted = $request->boolean('mark_completed');

        $record = ServiceRecord::firstOrNew(['maintenance_schedule_id' => $schedule->id]);
        $record->fill([
            'task_id' => $schedule->id,
            'parts_used' => $validated['parts_used'] ?? null,
            'labor_hours' => $validated['labor_hours'] ?? null,
            'labor_cost' => $validated['labor_cost'] ?? null,
            'parts_cost' => $validated['parts_cost'] ?? null,
            'notes' => $validated['notes'] ?? null,
        ]);

        if ($markCompleted) {
            $record->completed_at = now();
            if ($schedule->status !== 'cancelled') {
                $schedule->update(['status' => 'completed']);
            }
        }

        $record->save();

        return redirect()->route('schedules.show', $schedule)->with('status', 'Service record saved.');
    }
}
