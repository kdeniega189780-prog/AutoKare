<?php

namespace App\Http\Controllers;

use App\Models\MaintenanceSchedule;
use App\Models\ServiceRecord;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();

        // Customers + mechanics get their own portals.
        if ($user->isOwner()) {
            return redirect()->route('customer.vehicles');
        }
        if ($user->isMechanic()) {
            return redirect()->route('mechanic.tasks');
        }

        // Admin dashboard
        $vehicleCount = Vehicle::count();
        $pendingSchedules = MaintenanceSchedule::where('status', 'pending')->count();
        $overdueSchedules = MaintenanceSchedule::where('status', 'pending')
            ->where('scheduled_at', '<', now())
            ->count();

        $alerts = MaintenanceSchedule::with('vehicle')
            ->where('status', 'pending')
            ->orderBy('scheduled_at')
            ->limit(5)
            ->get()
            ->map(function (MaintenanceSchedule $s) {
                $isOverdue = $s->scheduled_at && $s->scheduled_at->isPast();
                $diff = $s->scheduled_at?->diffForHumans(['parts' => 1, 'short' => false]) ?? '';

                return [
                    'title' => $s->task_description . ' ' . ($isOverdue ? 'Overdue' : 'Due') . ' - ' . $s->vehicle->shortId(),
                    'subtitle' => ($isOverdue ? 'Due: ' : 'Due: ') . $diff,
                    'dot' => $isOverdue ? 'red' : 'yellow',
                ];
            })
            ->values()
            ->all();

        $recentActivity = ServiceRecord::with('maintenanceSchedule.vehicle')
            ->orderByDesc('completed_at')
            ->limit(5)
            ->get()
            ->map(function (ServiceRecord $r) {
                $vehicle = $r->maintenanceSchedule?->vehicle;
                return [
                    'vehicle' => $vehicle?->shortId(),
                    'text' => ($r->maintenanceSchedule?->task_description ?? 'Service') . ' Completed',
                    'date' => optional($r->completed_at)->format('M j, Y'),
                ];
            })
            ->values()
            ->all();

        return view('admin_dashboard', compact(
            'vehicleCount',
            'pendingSchedules',
            'overdueSchedules',
            'alerts',
            'recentActivity',
        ));
    }
}
