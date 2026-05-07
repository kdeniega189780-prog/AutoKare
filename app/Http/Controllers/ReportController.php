<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\ServiceRecord;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isMechanic()) {
            abort(403, 'Reporting is not available for mechanic accounts.');
        }

        $start = Carbon::now()->startOfMonth();
        $end = Carbon::now()->endOfMonth();
        $monthLabel = Carbon::now()->format('F Y');

        // ---- This-month KPIs ----
        $totalAppointments = MaintenanceSchedule::whereBetween('scheduled_at', [$start, $end])->count();
        $completedServices = MaintenanceSchedule::whereBetween('scheduled_at', [$start, $end])
            ->where('status', 'completed')->count();
        $pendingAppointments = MaintenanceSchedule::whereBetween('scheduled_at', [$start, $end])
            ->where('status', 'pending')->count();

        $totalRevenue = ServiceRecord::whereHas('maintenanceSchedule', fn ($q) => $q->whereBetween('scheduled_at', [$start, $end]))
            ->sum(DB::raw('COALESCE(labor_cost, 0) + COALESCE(parts_cost, 0)'));

        $activeCustomers = User::where('role', 'owner')->where('status', 'active')->count();
        $activeVehicles = Vehicle::where('status', 'active')->count();

        // ---- Maintenance breakdown by task_type ----
        $rawBreakdown = MaintenanceSchedule::select('task_type', DB::raw('COUNT(*) as cnt'))
            ->whereBetween('scheduled_at', [$start, $end])
            ->groupBy('task_type')
            ->get()
            ->keyBy('task_type');

        $taskTypeLabels = [
            'oil_change' => 'Oil Changes',
            'brake' => 'Brake Services',
            'tire_rotation' => 'Tire Rotations',
            'engine_diagnostic' => 'Engine Diagnostics',
            'air_filter' => 'Air Filter',
            'transmission' => 'Transmission',
            'general' => 'Other Services',
        ];

        $maintenanceBreakdown = [];
        foreach ($taskTypeLabels as $key => $label) {
            $cnt = (int) ($rawBreakdown->get($key)->cnt ?? 0);
            if ($cnt > 0 || in_array($key, ['oil_change', 'brake', 'tire_rotation', 'engine_diagnostic', 'general'], true)) {
                $maintenanceBreakdown[] = ['label' => $label, 'count' => $cnt];
            }
        }

        // ---- Top performing mechanics (this month) ----
        $topMechanics = User::query()
            ->where('role', 'mechanic')
            ->withCount(['assignedSchedules as completed_count' => function ($q) use ($start, $end) {
                $q->where('status', 'completed')->whereBetween('scheduled_at', [$start, $end]);
            }])
            ->get()
            ->map(function (User $m) use ($start, $end) {
                $revenue = ServiceRecord::whereHas('maintenanceSchedule', function ($q) use ($m, $start, $end) {
                    $q->where('mechanic_id', $m->id)->whereBetween('scheduled_at', [$start, $end])->where('status', 'completed');
                })->sum(DB::raw('COALESCE(labor_cost, 0) + COALESCE(parts_cost, 0)'));

                return [
                    'name' => $m->name,
                    'completed' => $m->completed_count,
                    'revenue' => (float) $revenue,
                ];
            })
            ->sortByDesc('completed')
            ->take(5)
            ->values()
            ->all();

        return view('reports.index', compact(
            'monthLabel',
            'totalAppointments',
            'completedServices',
            'pendingAppointments',
            'totalRevenue',
            'activeCustomers',
            'activeVehicles',
            'maintenanceBreakdown',
            'topMechanics',
        ));
    }

    public function export(Request $request): StreamedResponse
    {
        $user = $request->user();

        if ($user->isMechanic()) {
            abort(403, 'Reporting is not available for mechanic accounts.');
        }

        $filename = 'completed-maintenance-'.now()->format('Y-m-d').'.csv';

        $query = MaintenanceSchedule::query()
            ->with(['vehicle.owner', 'serviceRecord'])
            ->where('status', 'completed')
            ->when($user->isOwner(), fn ($q) => $q->whereHas('vehicle', fn ($v) => $v->where('owner_id', $user->id)))
            ->orderByDesc('updated_at');

        return response()->streamDownload(function () use ($query): void {
            $out = fopen('php://output', 'w');
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, ['Completed at', 'Vehicle', 'License plate', 'Owner', 'Task', 'Labor hours', 'Labor cost', 'Parts cost']);

            foreach ($query->cursor() as $schedule) {
                $record = $schedule->serviceRecord;
                fputcsv($out, [
                    $record?->completed_at?->toDateTimeString() ?? $schedule->updated_at->toDateTimeString(),
                    trim($schedule->vehicle->make.' '.$schedule->vehicle->model),
                    $schedule->vehicle->license_plate,
                    $schedule->vehicle->owner->name,
                    $schedule->task_description,
                    $record?->labor_hours ?? '',
                    $record?->labor_cost ?? '',
                    $record?->parts_cost ?? '',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
