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

    <div class="vms-banner vms-banner-info">
        <div class="d-flex align-items-start">
            <i class="fas fa-chart-bar mr-3 mt-1 text-info"></i>
            <div>
                <h5>Admin Reports</h5>
                <p>
                    View real-time system statistics and business metrics. All data is calculated from current
                    database records.
                </p>
            </div>
        </div>
    </div>

    <div class="card card-outline card-primary">
        <div class="card-header">
            <h3 class="card-title font-weight-bold">
                <i class="fas fa-calendar-alt mr-1"></i> This Month ({{ $monthLabel }})
            </h3>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $totalAppointments }}</h3>
                            <p>Total Appointments</p>
                        </div>
                        <div class="icon"><i class="fas fa-calendar-check"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $completedServices }}</h3>
                            <p>Completed Services</p>
                        </div>
                        <div class="icon"><i class="fas fa-check-circle"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $pendingAppointments }}</h3>
                            <p>Pending Appointments</p>
                        </div>
                        <div class="icon"><i class="fas fa-clock"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            <h3>${{ number_format($totalRevenue, 0) }}</h3>
                            <p>Total Revenue</p>
                        </div>
                        <div class="icon"><i class="fas fa-dollar-sign"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-secondary">
                        <div class="inner">
                            <h3>{{ $activeCustomers }}</h3>
                            <p>Active Customers</p>
                        </div>
                        <div class="icon"><i class="fas fa-users"></i></div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="small-box bg-dark">
                        <div class="inner">
                            <h3>{{ $activeVehicles }}</h3>
                            <p>Active Vehicles</p>
                        </div>
                        <div class="icon"><i class="fas fa-car"></i></div>
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
                                    <td class="font-weight-bold">${{ number_format($m['revenue'], 0) }}</td>
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
