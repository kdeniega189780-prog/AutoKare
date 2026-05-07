<x-app-layout>
    @php($u = Auth::user())

    <x-slot name="header">
        <h1 class="m-0">{{ __('Dashboard') }}</h1>
    </x-slot>

    <div class="row">
        @if ($u->isAdmin())
            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 text-muted">
                            <i class="fas fa-car"></i>
                        </div>
                        <div>
                            <div class="small text-muted">{{ __('Total Vehicles') }}</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $vehicleCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 text-muted">
                            <i class="fas fa-calendar-alt"></i>
                        </div>
                        <div>
                            <div class="small text-muted">{{ __('Upcoming Maintenance') }}</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $pendingSchedules }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card mb-3">
                    <div class="card-body d-flex align-items-center">
                        <div class="mr-3 text-muted">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div>
                            <div class="small text-muted">{{ __('Overdue') }}</div>
                            <div class="h4 mb-0 font-weight-bold">{{ $overdueSchedules ?? 0 }}</div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Alerts & Notifications') }}</h3>
                    </div>
                    <div class="card-body">
                        @forelse(($alerts ?? []) as $alert)
                            <div class="border rounded p-3 mb-2 bg-light">
                                <div class="font-weight-bold">{{ $alert['title'] ?? '' }}</div>
                                <div class="text-muted small">{{ $alert['subtitle'] ?? '' }}</div>
                            </div>
                        @empty
                            <div class="text-muted">{{ __('No alerts right now.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @elseif ($u->isMechanic())
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">{{ __('Assigned Tasks') }}</h3>
                    </div>
                    <div class="card-body">
                        @forelse(($assignedTasks ?? []) as $task)
                            <div class="border rounded p-3 mb-3">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <div class="font-weight-bold">{{ $task['vehicle'] ?? '' }}</div>
                                        <div class="text-muted">{{ $task['title'] ?? '' }}</div>
                                        <div class="text-muted small">{{ __('Due Date') }}: {{ $task['due'] ?? '' }}</div>
                                    </div>
                                    <div class="text-right">
                                        @if (!empty($task['priority']))
                                            <span class="badge badge-light">{{ $task['priority'] }}</span>
                                        @endif
                                        <div class="text-muted small mt-2">{{ __('Task ID') }}: {{ $task['id'] ?? '' }}</div>
                                    </div>
                                </div>
                                <div class="mt-3 d-flex gap-2">
                                    <a href="{{ $task['startUrl'] ?? route('schedules.index') }}" class="btn btn-dark btn-sm">{{ __('Start Task') }}</a>
                                    <a href="{{ $task['assignUrl'] ?? route('schedules.index') }}" class="btn btn-primary btn-sm">{{ __('Assign to Team') }}</a>
                                    <a href="{{ $task['detailsUrl'] ?? route('schedules.index') }}" class="btn btn-outline-secondary btn-sm">{{ __('View Details') }}</a>
                                </div>
                            </div>
                        @empty
                            <div class="text-muted">{{ __('No assigned tasks.') }}</div>
                        @endforelse
                    </div>
                </div>
            </div>
        @else
            <div class="col-12">
                <div class="d-flex align-items-center justify-content-between mb-3 flex-wrap">
                    <h3 class="mb-0">{{ __('My Vehicles') }}</h3>
                    @can('create', App\Models\Vehicle::class)
                        <a href="{{ route('vehicles.create') }}" class="btn btn-light border">
                            <i class="fas fa-plus mr-1"></i>{{ __('Add Vehicle') }}
                        </a>
                    @endcan
                </div>

                @forelse(($myVehicles ?? []) as $v)
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <div class="font-weight-bold">{{ $v['title'] ?? '' }}</div>
                                    <div class="text-muted small">{{ __('License Plate') }}: {{ $v['plate'] ?? '' }}</div>
                                </div>
                                @if (!empty($v['status']))
                                    <span class="badge badge-light">{{ $v['status'] }}</span>
                                @endif
                            </div>

                            @if (!empty($v['alert']))
                                <div class="alert alert-danger mt-3 mb-0">
                                    <div class="font-weight-bold">{{ $v['alert']['title'] ?? '' }}</div>
                                    <div class="small">{{ $v['alert']['text'] ?? '' }}</div>
                                </div>
                            @endif

                            <div class="row mt-3">
                                <div class="col-md-6">
                                    <div class="border rounded p-2">
                                        <div class="text-muted small">{{ __('Last Service') }}</div>
                                        <div class="font-weight-bold">{{ $v['lastService'] ?? __('—') }}</div>
                                    </div>
                                </div>
                                <div class="col-md-6 mt-2 mt-md-0">
                                    <div class="border rounded p-2">
                                        <div class="text-muted small">{{ __('Next Service') }}</div>
                                        <div class="font-weight-bold">{{ $v['nextService'] ?? __('—') }}</div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <a href="{{ $v['scheduleUrl'] ?? route('schedules.index') }}" class="btn btn-dark btn-sm">
                                    {{ !empty($v['alert']) ? __('Schedule Service Now') : __('Schedule Service') }}
                                </a>
                                <a href="{{ $v['detailsUrl'] ?? route('vehicles.index') }}" class="btn btn-outline-secondary btn-sm">
                                    {{ __('View Details') }}
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-muted">{{ __('No vehicles yet.') }}</div>
                @endforelse
            </div>
        @endif
    </div>
</x-app-layout>
