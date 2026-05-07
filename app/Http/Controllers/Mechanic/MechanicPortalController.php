<?php

namespace App\Http\Controllers\Mechanic;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceSchedule;
use App\Models\ServicePart;
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class MechanicPortalController extends Controller
{
    /** ---------------------------------------------------------------- */
    /** Pages                                                              */
    /** ---------------------------------------------------------------- */

    public function assigned(Request $request): View
    {
        $user = $this->mechanic($request);

        $tasks = MaintenanceSchedule::with('vehicle.owner')
            ->where(fn ($q) => $q->where('assigned_mechanic_id', $user->id)->orWhere('mechanic_id', $user->id))
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->get();

        return view('mechanic_tasks', compact('tasks'));
    }

    public function inProgress(Request $request): View
    {
        $user = $this->mechanic($request);

        $tasks = MaintenanceSchedule::with('vehicle.owner')
            ->where(fn ($q) => $q->where('assigned_mechanic_id', $user->id)->orWhere('mechanic_id', $user->id))
            ->where('status', 'in_progress')
            ->orderBy('scheduled_at')
            ->get();

        return view('mechanic_progress', compact('tasks'));
    }

    public function completed(Request $request): View
    {
        $user = $this->mechanic($request);

        $tasks = MaintenanceSchedule::with(['vehicle.owner', 'serviceRecord', 'mechanic'])
            ->where(fn ($q) => $q->where('assigned_mechanic_id', $user->id)->orWhere('mechanic_id', $user->id))
            ->where('status', 'completed')
            ->orderByDesc('scheduled_at')
            ->get();

        return view('mechanic_completed', compact('tasks'));
    }

    public function teamOverview(Request $request): View
    {
        $user = $this->mechanic($request);

        $teamMembers = User::query()
            ->where('role', 'mechanic')
            ->where('id', '!=', $user->id)
            ->orderBy('name')
            ->get()
            ->map(fn (User $m) => [
                'id' => $m->id,
                'name' => $m->name,
                'role' => 'Mechanic',
                'status' => $m->current_status === 'working' ? 'Working' : ($m->current_status === 'on_break' ? 'On Break' : 'Off Duty'),
                'status_key' => $m->current_status,
            ])
            ->all();

        $teamTasks = MaintenanceSchedule::with(['vehicle', 'assignedMechanic'])
            ->where('created_by', $user->id)
            ->whereIn('status', ['pending', 'in_progress', 'completed'])
            ->orderByDesc('scheduled_at')
            ->limit(20)
            ->get();

        return view('mechanic_team_overview', compact('teamMembers', 'teamTasks'));
    }

    public function teamReports(Request $request): View
    {
        $user = $this->mechanic($request);

        $start = Carbon::now()->startOfWeek();
        $end = Carbon::now()->endOfWeek();

        $weekTasks = MaintenanceSchedule::whereBetween('scheduled_at', [$start, $end]);
        $summary = [
            'total' => (clone $weekTasks)->count(),
            'completed' => (clone $weekTasks)->where('status', 'completed')->count(),
            'in_progress' => (clone $weekTasks)->where('status', 'in_progress')->count(),
            'assigned' => (clone $weekTasks)->where('status', 'pending')->count(),
            'avg_time' => $this->avgTime($start, $end),
            'active_mechanics' => User::where('role', 'mechanic')->where('status', 'active')->count(),
        ];

        $mechanics = User::query()
            ->where('role', 'mechanic')
            ->orderBy('name')
            ->get()
            ->map(function (User $m) use ($start, $end) {
                $base = MaintenanceSchedule::where('mechanic_id', $m->id)->whereBetween('scheduled_at', [$start, $end]);
                $hours = (clone $base)->where('status', 'completed')->whereNotNull('started_at')->whereNotNull('completed_at')
                    ->get()
                    ->sum(fn ($s) => $s->started_at->diffInMinutes($s->completed_at) / 60);

                return [
                    'name' => $m->name,
                    'completed' => (clone $base)->where('status', 'completed')->count(),
                    'in_progress' => (clone $base)->where('status', 'in_progress')->count(),
                    'assigned' => (clone $base)->where('status', 'pending')->count(),
                    'hours' => round($hours, 0) . 'h',
                ];
            })
            ->sortByDesc(fn ($m) => $m['completed'])
            ->take(8)
            ->values()
            ->all();

        $rawBreakdown = MaintenanceSchedule::select('task_type', DB::raw('COUNT(*) as cnt'))
            ->whereBetween('scheduled_at', [$start, $end])
            ->groupBy('task_type')
            ->get()
            ->keyBy('task_type');

        $serviceBreakdown = collect([
            'oil_change' => 'Oil Changes',
            'brake' => 'Brake Services',
            'tire_rotation' => 'Tire Rotations',
            'engine_diagnostic' => 'Engine Diagnostics',
            'air_filter' => 'Air Filter',
            'transmission' => 'Transmission Service',
            'general' => 'Other',
        ])->map(fn ($lbl, $key) => [
            'label' => $lbl,
            'count' => (int) ($rawBreakdown->get($key)->cnt ?? 0),
        ])->values()->all();

        return view('mechanic_team_reports', compact('summary', 'mechanics', 'serviceBreakdown'));
    }

    public function customerVehicles(Request $request): View
    {
        $this->mechanic($request);

        $vehicles = Vehicle::with('owner')->orderBy('make')->get();

        return view('mechanic.customer-vehicles', compact('vehicles'));
    }

    /** ---------------------------------------------------------------- */
    /** Modal endpoints                                                    */
    /** ---------------------------------------------------------------- */

    public function detailsModal(MaintenanceSchedule $schedule, Request $request): View
    {
        $this->ensureOwn($schedule, $request);
        $schedule->load('vehicle.owner');
        return view('modals.mechanic.view-details', compact('schedule'));
    }

    public function startModal(MaintenanceSchedule $schedule, Request $request): View
    {
        $this->ensureOwn($schedule, $request);
        $schedule->load('vehicle.owner');
        return view('modals.mechanic.start-task', compact('schedule'));
    }

    public function assignModal(MaintenanceSchedule $schedule, Request $request): View
    {
        $this->ensureOwn($schedule, $request);
        $schedule->load('vehicle.owner');
        $teamMembers = User::where('role', 'mechanic')->orderBy('name')->get();
        return view('modals.mechanic.assign-task', compact('schedule', 'teamMembers'));
    }

    public function noteModal(MaintenanceSchedule $schedule, Request $request): View
    {
        $this->ensureOwn($schedule, $request);
        $schedule->load('vehicle.owner');
        return view('modals.mechanic.add-notes', compact('schedule'));
    }

    public function completeModal(MaintenanceSchedule $schedule, Request $request): View
    {
        $this->ensureOwn($schedule, $request);
        $schedule->load('vehicle.owner');
        return view('modals.mechanic.mark-complete', compact('schedule'));
    }

    public function reportModal(MaintenanceSchedule $schedule, Request $request): View
    {
        $this->ensureOwn($schedule, $request);
        $schedule->load(['vehicle.owner', 'serviceRecord.parts.part', 'mechanic']);
        return view('modals.mechanic.service-report', compact('schedule'));
    }

    /** ---------------------------------------------------------------- */
    /** Actions                                                            */
    /** ---------------------------------------------------------------- */

    public function startTask(MaintenanceSchedule $schedule, Request $request): RedirectResponse
    {
        $this->ensureOwn($schedule, $request);

        $validated = $request->validate([
            'start_time' => ['nullable', 'string', 'max:32'],
            'initial_notes' => ['nullable', 'string', 'max:2000'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
        ]);

        $schedule->update([
            'status' => 'in_progress',
            'started_at' => $this->resolveTimeInput($validated['start_time'] ?? null) ?? now(),
            'initial_notes' => $validated['initial_notes'] ?? $schedule->initial_notes,
            'estimated_minutes' => $validated['estimated_minutes'] ?? $schedule->estimated_minutes,
        ]);

        return redirect()->route('mechanic.inProgress')->with('status', 'Task started.');
    }

    public function assignTask(MaintenanceSchedule $schedule, Request $request): RedirectResponse
    {
        $this->ensureOwn($schedule, $request);

        $validated = $request->validate([
            'mechanic_id' => ['required', 'exists:users,id'],
            'initial_notes' => ['nullable', 'string', 'max:2000'],
            'estimated_minutes' => ['nullable', 'integer', 'min:1', 'max:600'],
        ]);

        $schedule->update([
            'assigned_mechanic_id' => $validated['mechanic_id'],
            'mechanic_id' => $validated['mechanic_id'],
            'initial_notes' => $validated['initial_notes'] ?? $schedule->initial_notes,
            'estimated_minutes' => $validated['estimated_minutes'] ?? $schedule->estimated_minutes,
        ]);

        return redirect()->route('mechanic.teamOverview')->with('status', 'Task assigned.');
    }

    public function addNote(MaintenanceSchedule $schedule, Request $request): RedirectResponse
    {
        $this->ensureOwn($schedule, $request);

        $validated = $request->validate([
            'progress_notes' => ['required', 'string', 'max:2000'],
        ]);

        $schedule->update(['progress_notes' => $validated['progress_notes']]);

        return redirect()->route('mechanic.inProgress')->with('status', 'Note added.');
    }

    public function completeTask(MaintenanceSchedule $schedule, Request $request): RedirectResponse
    {
        $this->ensureOwn($schedule, $request);

        $validated = $request->validate([
            'work_summary' => ['required', 'string', 'max:5000'],
            'parts_used' => ['nullable', 'string', 'max:2000'],
            'labor_cost' => ['nullable', 'numeric', 'min:0'],
            'parts_cost' => ['nullable', 'numeric', 'min:0'],
            'recommendations' => ['nullable', 'string', 'max:5000'],
            'end_time' => ['nullable', 'string', 'max:64'],
        ]);

        $endTime = $this->resolveTimeInput($validated['end_time'] ?? null) ?? now();

        $schedule->update([
            'status' => 'completed',
            'completed_at' => $endTime,
        ]);

        ServiceRecord::updateOrCreate(
            ['maintenance_schedule_id' => $schedule->id],
            [
                'task_id' => $schedule->id,
                'service_date' => $schedule->scheduled_at?->toDateString() ?? now()->toDateString(),
                'odometer' => $schedule->vehicle?->mileage,
                'parts_used' => $validated['parts_used'] ?? null,
                'work_summary' => $validated['work_summary'],
                'recommendations' => $validated['recommendations'] ?? null,
                'labor_cost' => $validated['labor_cost'] ?? 0,
                'parts_cost' => $validated['parts_cost'] ?? 0,
                'completed_at' => $endTime,
            ]
        );

        return redirect()->route('mechanic.completed')->with('status', 'Task completed.');
    }

    public function updateTeamStatus(MaintenanceSchedule $schedule, Request $request): RedirectResponse
    {
        $this->ensureOwn($schedule, $request);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,in_progress,completed,cancelled'],
        ]);

        $update = ['status' => $validated['status']];
        if ($validated['status'] === 'in_progress' && ! $schedule->started_at) {
            $update['started_at'] = now();
        }
        if ($validated['status'] === 'completed' && ! $schedule->completed_at) {
            $update['completed_at'] = now();
        }
        $schedule->update($update);

        return back()->with('status', 'Task status updated.');
    }

    /** ---------------------------------------------------------------- */
    /** Helpers                                                            */
    /** ---------------------------------------------------------------- */

    private function mechanic(Request $request): User
    {
        $user = $request->user();
        abort_unless($user && $user->isMechanic(), 403);
        return $user;
    }

    private function ensureOwn(MaintenanceSchedule $schedule, Request $request): void
    {
        $user = $this->mechanic($request);
        // Senior mechanics can act on tasks they assigned; mechanics on their own.
        $isAssignee = in_array($user->id, [$schedule->mechanic_id, $schedule->assigned_mechanic_id], true);
        $isAssigner = $schedule->created_by === $user->id;
        abort_unless($isAssignee || $isAssigner, 403);
    }

    /**
     * Accepts either an ISO datetime ("2026-01-26T10:24"), a localized
     * "g:i a" time ("10:24 am"), or a 24h time ("22:30") and returns a Carbon
     * with today's date. Returns null on empty/invalid input.
     */
    private function resolveTimeInput(?string $value): ?Carbon
    {
        $value = trim((string) $value);
        if ($value === '') {
            return null;
        }

        // Already a parseable datetime.
        try {
            $parsed = Carbon::parse($value);
            // If the user only sent a time (no date), Carbon::parse uses today's date,
            // which is what we want.
            return $parsed;
        } catch (\Throwable $e) {
            // Try common time-only formats.
            foreach (['g:i a', 'g:ia', 'h:i a', 'H:i'] as $fmt) {
                try {
                    return Carbon::createFromFormat($fmt, strtolower($value))
                        ?->setDate(now()->year, now()->month, now()->day);
                } catch (\Throwable $e2) {
                    continue;
                }
            }
            return null;
        }
    }

    private function avgTime(Carbon $start, Carbon $end): string
    {
        $list = MaintenanceSchedule::whereBetween('scheduled_at', [$start, $end])
            ->where('status', 'completed')
            ->whereNotNull('started_at')
            ->whereNotNull('completed_at')
            ->get();
        if ($list->isEmpty()) return '0h';
        $minutes = $list->sum(fn ($s) => $s->started_at->diffInMinutes($s->completed_at));
        return round(($minutes / max($list->count(), 1)) / 60, 1) . 'h';
    }
}
