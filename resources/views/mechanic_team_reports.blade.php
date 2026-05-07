<x-app-layout>
    <h1 class="vms-page-title mb-1">{{ __('Team Reports') }}</h1>
    <h2 class="font-weight-bold text-dark mt-3 mb-1" style="font-size:18px;">{{ __('Team Performance Overview') }}</h2>
    <div class="text-muted mb-3" style="font-size:13px;">{{ __("Monitor your team's productivity and task completion") }}</div>

    <div class="vms-banner-info-blue mb-4">
        <strong>{{ __('Senior Mechanic Reports:') }}</strong>
        {{ __('View real-time team performance metrics and task statistics. Monitor workload distribution and identify areas for improvement.') }}
    </div>

    <div class="vms-card vms-card-pad mb-4">
        <div class="font-weight-bold text-dark mb-3">
            <i class="far fa-clock mr-1"></i> {{ __('This Week Summary') }}
        </div>
        <div class="row" style="margin:0 -6px;">
            <div class="col-md-4 px-2 mb-2">
                <div class="vms-summary-tile vms-st-blue">
                    <div class="vms-st-head">
                        {{ __('Total Tasks') }}
                        <i class="fas fa-wrench"></i>
                    </div>
                    <div class="vms-st-value">{{ $summary['total'] }}</div>
                    <div class="vms-st-sub">{{ __('↑ 12% from last week') }}</div>
                </div>
            </div>
            <div class="col-md-4 px-2 mb-2">
                <div class="vms-summary-tile vms-st-green">
                    <div class="vms-st-head">
                        {{ __('Completed') }}
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="vms-st-value">{{ $summary['completed'] }}</div>
                    <div class="vms-st-sub">
                        @php $rate = $summary['total'] ? round(($summary['completed'] / $summary['total']) * 100) : 0; @endphp
                        {{ $rate }}% {{ __('completion rate') }}
                    </div>
                </div>
            </div>
            <div class="col-md-4 px-2 mb-2">
                <div class="vms-summary-tile vms-st-orange">
                    <div class="vms-st-head">
                        {{ __('In Progress') }}
                        <i class="far fa-clock"></i>
                    </div>
                    <div class="vms-st-value">{{ $summary['in_progress'] }}</div>
                    <div class="vms-st-sub">{{ __('Being worked on') }}</div>
                </div>
            </div>
            <div class="col-md-4 px-2 mb-2">
                <div class="vms-summary-tile vms-st-purple">
                    <div class="vms-st-head">
                        {{ __('Assigned (Pending)') }}
                        <i class="fas fa-info-circle"></i>
                    </div>
                    <div class="vms-st-value">{{ $summary['assigned'] }}</div>
                    <div class="vms-st-sub">{{ __('Not yet started') }}</div>
                </div>
            </div>
            <div class="col-md-4 px-2 mb-2">
                <div class="vms-summary-tile vms-st-gray">
                    <div class="vms-st-head">
                        {{ __('Avg. Time per Task') }}
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <div class="vms-st-value">{{ $summary['avg_time'] }}</div>
                    <div class="vms-st-sub">{{ __('↓ 0.5h improvement') }}</div>
                </div>
            </div>
            <div class="col-md-4 px-2 mb-2">
                <div class="vms-summary-tile vms-st-gray">
                    <div class="vms-st-head">
                        {{ __('Active Mechanics') }}
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="vms-st-value">{{ $summary['active_mechanics'] }}</div>
                    <div class="vms-st-sub">{{ __('All available') }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="vms-card has-table mb-4">
        <div class="px-4 pt-3 pb-2 font-weight-bold text-dark" style="border-bottom:1px solid #f3f4f6;">
            <i class="fas fa-users mr-1"></i> {{ __('Individual Mechanic Performance') }}
        </div>
        <table class="vms-table mb-0">
            <thead>
                <tr>
                    <th>{{ __('Mechanic') }}</th>
                    <th>{{ __('Completed') }}</th>
                    <th>{{ __('In Progress') }}</th>
                    <th>{{ __('Assigned') }}</th>
                    <th>{{ __('Total Hours') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($mechanics as $m)
                    <tr>
                        <td class="font-weight-bold text-dark">{{ $m['name'] }}</td>
                        <td><span class="vms-pill-solid-green">{{ $m['completed'] }}</span></td>
                        <td><span class="vms-pill-solid-yellow">{{ $m['in_progress'] }}</span></td>
                        <td><span class="vms-pill-purple">{{ $m['assigned'] }}</span></td>
                        <td class="font-weight-bold text-dark">{{ $m['hours'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="vms-card vms-card-pad">
        <div class="font-weight-bold text-dark mb-3">
            <i class="fas fa-wrench mr-1"></i> {{ __('Service Type Breakdown (This Week)') }}
        </div>
        @foreach ($serviceBreakdown as $row)
            <div class="d-flex justify-content-between align-items-center py-2"
                 style="border-bottom:1px solid #f3f4f6; font-size:13px;">
                <span class="text-dark">{{ $row['label'] }}</span>
                <span class="font-weight-bold text-dark">{{ $row['count'] }} {{ __('tasks') }}</span>
            </div>
        @endforeach
    </div>
</x-app-layout>
