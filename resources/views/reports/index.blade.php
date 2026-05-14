<x-app-layout>
    <div class="vms-page-header">
        <div>
            <h1 class="vms-page-title">System Overview</h1>
            <p class="vms-page-subtitle">Key performance indicators and statistics</p>
        </div>
        <a href="{{ route('reports.export') }}" class="btn btn-default">
            <i class="fas fa-download mr-1"></i> Export CSV
        </a>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-calendar-alt mr-1"></i> This Month ({{ $monthLabel }})
            </h3>
        </div>
        <div class="card-body">
            <div class="vms-icon-tiles">
                <div class="vms-icon-tile">
                    <div class="vms-icon-tile-left">
                        <span class="vms-icon-tile-icon vms-it-blue"><i class="fas fa-calendar-check"></i></span>
                        <div class="vms-icon-tile-text">
                            <div class="vms-icon-tile-label">Total Appointments</div>
                            <div class="vms-icon-tile-value">{{ $totalAppointments }}</div>
                        </div>
                    </div>
                </div>
                <div class="vms-icon-tile">
                    <div class="vms-icon-tile-left">
                        <span class="vms-icon-tile-icon vms-it-green"><i class="fas fa-check-circle"></i></span>
                        <div class="vms-icon-tile-text">
                            <div class="vms-icon-tile-label">Completed Services</div>
                            <div class="vms-icon-tile-value">{{ $completedServices }}</div>
                        </div>
                    </div>
                </div>
                <div class="vms-icon-tile">
                    <div class="vms-icon-tile-left">
                        <span class="vms-icon-tile-icon vms-it-amber"><i class="fas fa-clock"></i></span>
                        <div class="vms-icon-tile-text">
                            <div class="vms-icon-tile-label">Pending Appointments</div>
                            <div class="vms-icon-tile-value">{{ $pendingAppointments }}</div>
                        </div>
                    </div>
                </div>
                <div class="vms-icon-tile">
                    <div class="vms-icon-tile-left">
                        <span class="vms-icon-tile-icon vms-it-purple"><i class="fas fa-dollar-sign"></i></span>
                        <div class="vms-icon-tile-text">
                            <div class="vms-icon-tile-label">Total Revenue</div>
                            <div class="vms-icon-tile-value"><x-money :value="$totalRevenue" :decimals="0" /></div>
                        </div>
                    </div>
                </div>
                <div class="vms-icon-tile">
                    <div class="vms-icon-tile-left">
                        <span class="vms-icon-tile-icon vms-it-gray"><i class="fas fa-users"></i></span>
                        <div class="vms-icon-tile-text">
                            <div class="vms-icon-tile-label">Active Customers</div>
                            <div class="vms-icon-tile-value">{{ $activeCustomers }}</div>
                        </div>
                    </div>
                </div>
                <div class="vms-icon-tile">
                    <div class="vms-icon-tile-left">
                        <span class="vms-icon-tile-icon vms-it-blue"><i class="fas fa-car"></i></span>
                        <div class="vms-icon-tile-text">
                            <div class="vms-icon-tile-label">Active Vehicles</div>
                            <div class="vms-icon-tile-value">{{ $activeVehicles }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-wrench mr-1"></i> Maintenance Breakdown
                    </h3>
                </div>
                <div class="card-body p-0">
                    <ul class="list-group list-group-flush">
                        @foreach ($maintenanceBreakdown as $row)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <span>{{ $row['label'] }}</span>
                                <span class="badge badge-primary badge-pill p-2">{{ $row['count'] }} services</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-outline card-warning">
                <div class="card-header">
                    <h3 class="card-title font-weight-bold">
                        <i class="fas fa-trophy mr-1"></i> Top Performing Mechanics ({{ $monthLabel }})
                    </h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-hover vms-light mb-0">
                        <thead>
                            <tr>
                                <th>Mechanic</th>
                                <th>Completed</th>
                                <th>Revenue</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($topMechanics as $m)
                                <tr>
                                    <td class="font-weight-bold text-dark">{{ $m['name'] }}</td>
                                    <td>{{ $m['completed'] }} tasks</td>
                                    <td class="font-weight-bold"><x-money :value="$m['revenue']" :decimals="0" /></td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="text-center text-muted py-3">No mechanic activity this month.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
