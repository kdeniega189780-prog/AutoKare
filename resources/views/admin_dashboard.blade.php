<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">Dashboard</h1>
            <p class="vms-page-subtitle">System overview and quick stats</p>
        </div>
    </div>

    <div class="vms-icon-tiles mb-3">
        <div class="vms-icon-tile">
            <div class="vms-icon-tile-left">
                <span class="vms-icon-tile-icon vms-it-blue"><i class="fas fa-car"></i></span>
                <div class="vms-icon-tile-text">
                    <div class="vms-icon-tile-label">Total Vehicles</div>
                    <div class="vms-icon-tile-value">{{ $vehicleCount }}</div>
                </div>
            </div>
        </div>
        <div class="vms-icon-tile">
            <div class="vms-icon-tile-left">
                <span class="vms-icon-tile-icon vms-it-green"><i class="fas fa-calendar-check"></i></span>
                <div class="vms-icon-tile-text">
                    <div class="vms-icon-tile-label">Upcoming Maintenance</div>
                    <div class="vms-icon-tile-value">{{ $pendingSchedules }}</div>
                </div>
            </div>
        </div>
        <div class="vms-icon-tile">
            <div class="vms-icon-tile-left">
                <span class="vms-icon-tile-icon vms-it-red"><i class="fas fa-exclamation-triangle"></i></span>
                <div class="vms-icon-tile-text">
                    <div class="vms-icon-tile-label">Overdue</div>
                    <div class="vms-icon-tile-value">{{ $overdueSchedules }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="card card-outline card-warning">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-bell mr-1"></i> Alerts &amp; Notifications</h3>
        </div>
        <div class="card-body p-2">
            @forelse ($alerts as $alert)
                @php $dotColor = ($alert['dot'] ?? '') === 'red' ? 'text-danger' : 'text-warning'; @endphp
                <div class="d-flex align-items-start px-3 py-2 border-bottom">
                    <i class="fas fa-circle {{ $dotColor }} mt-2 mr-3" style="font-size:8px;"></i>
                    <div>
                        <div class="font-weight-bold text-dark">{{ $alert['title'] }}</div>
                        <small class="text-muted">{{ $alert['subtitle'] }}</small>
                    </div>
                </div>
            @empty
                <p class="text-muted mb-0 px-3 py-3">No alerts right now.</p>
            @endforelse
        </div>
    </div>

    <div class="card card-outline card-success">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-history mr-1"></i> Recent Activity</h3>
        </div>
        <div class="card-body p-0">
            @if (count($recentActivity))
                <table class="table table-hover vms-light mb-0">
                    <thead>
                        <tr>
                            <th>Vehicle</th>
                            <th>Activity</th>
                            <th class="text-right">Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentActivity as $row)
                            <tr>
                                <td class="font-weight-bold text-dark">{{ $row['vehicle'] }}</td>
                                <td class="text-muted">{{ $row['text'] }}</td>
                                <td class="text-right text-muted">{{ $row['date'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-muted mb-0 px-3 py-3">No recent activity.</p>
            @endif
        </div>
    </div>
</x-app-layout>
