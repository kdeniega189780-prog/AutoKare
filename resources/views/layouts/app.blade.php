<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name') }}</title>

    @include('layouts.partials.vms-fonts')
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/plugins/toastr/toastr.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/dist/css/adminlte.min.css">
    <link rel="stylesheet" href="{{ $refAsset }}/dist/css/custom.css">
</head>
@php
    use App\Models\MaintenanceSchedule;
    $u = Auth::user();

    $roleBadgeClass = match ($u->role) {
        'admin' => 'badge-danger',
        'mechanic' => 'badge-info',
        'owner' => 'badge-purple',
        default => 'badge-secondary',
    };

    $brandIcon = match ($u->role) {
        'admin' => 'fa-shield-alt',
        'mechanic' => 'fa-wrench',
        'owner' => 'fa-user',
        default => 'fa-car',
    };

    $todayCounts = null;
    if ($u->isMechanic()) {
        $todayCounts = [
            'my_tasks' => MaintenanceSchedule::where('mechanic_id', $u->id)->where('status', 'pending')->count(),
            'in_progress' => MaintenanceSchedule::where('mechanic_id', $u->id)->where('status', 'in_progress')->count(),
            'completed' => MaintenanceSchedule::where('mechanic_id', $u->id)->where('status', 'completed')->count(),
            'team_tasks' => MaintenanceSchedule::where('created_by', $u->id)->whereIn('status', ['pending', 'in_progress'])->count(),
        ];
    }

    $isActive = fn (bool $cond) => $cond ? 'active' : '';
@endphp
<body class="hold-transition layout-fixed vms-static">
<div class="wrapper">
    <nav class="main-header navbar navbar-expand navbar-dark bg-primary">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item d-none d-sm-flex align-items-center mr-2 text-right">
                <div class="lh-1">
                    <div class="font-weight-bold text-white">{{ $u->name }}</div>
                    <div class="small text-white-50">{{ strtolower($u->roleLabel()) }}</div>
                </div>
            </li>
            <li class="nav-item">
                <span class="vms-avatar"><i class="fas fa-user"></i></span>
            </li>
            <li class="nav-item ml-2">
                <form method="POST" action="{{ route('logout') }}" class="form-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline-light btn-sm">
                        <i class="fas fa-sign-out-alt mr-1"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </nav>

    <aside class="main-sidebar elevation-1">
        <div class="vms-sidebar-brand">
            <span class="vms-brand-icon mr-2"><i class="fas {{ $brandIcon }}"></i></span>
            <span class="vms-brand-text">{{ config('app.name') }}</span>
            <span class="badge {{ $roleBadgeClass }} ml-2 vms-role-badge">{{ $u->roleBadgeText() }}</span>
        </div>
        <div class="sidebar">
            <nav class="mt-3">
                <ul class="nav nav-pills nav-sidebar flex-column vms-side-nav" role="menu">
                    @if ($u->isAdmin())
                        <li class="nav-item">
                            <a href="{{ route('dashboard') }}" class="nav-link {{ $isActive(request()->routeIs('dashboard')) }}">
                                <i class="nav-icon fas fa-tachometer-alt"></i>
                                <p>Dashboard</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('vehicles.index') }}" class="nav-link {{ $isActive(request()->routeIs('vehicles.*')) }}">
                                <i class="nav-icon fas fa-car"></i>
                                <p>Vehicles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ $isActive(request()->routeIs('admin.appointments.*')) }}">
                                <i class="nav-icon fas fa-calendar-check"></i>
                                <p>Appointments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.maintenance.index') }}" class="nav-link {{ $isActive(request()->routeIs('admin.maintenance.*')) }}">
                                <i class="nav-icon fas fa-wrench"></i>
                                <p>Maintenance</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('reports.index') }}" class="nav-link {{ $isActive(request()->routeIs('reports.*')) }}">
                                <i class="nav-icon fas fa-chart-bar"></i>
                                <p>Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users.index') }}" class="nav-link {{ $isActive(request()->routeIs('admin.users.*')) }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>User Management</p>
                            </a>
                        </li>
                    @elseif ($u->isMechanic())
                        <li class="nav-item">
                            <a href="{{ route('mechanic.tasks') }}" class="nav-link {{ $isActive(request()->routeIs('mechanic.tasks')) }}">
                                <i class="nav-icon fas fa-tasks"></i>
                                <p>My Assigned Tasks</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mechanic.inProgress') }}" class="nav-link {{ $isActive(request()->routeIs('mechanic.inProgress')) }}">
                                <i class="nav-icon fas fa-spinner"></i>
                                <p>In Progress</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mechanic.completed') }}" class="nav-link {{ $isActive(request()->routeIs('mechanic.completed')) }}">
                                <i class="nav-icon fas fa-check-circle"></i>
                                <p>Completed</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mechanic.teamOverview') }}" class="nav-link {{ $isActive(request()->routeIs('mechanic.teamOverview')) }}">
                                <i class="nav-icon fas fa-users"></i>
                                <p>Team Overview</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mechanic.teamReports') }}" class="nav-link {{ $isActive(request()->routeIs('mechanic.teamReports')) }}">
                                <i class="nav-icon fas fa-chart-line"></i>
                                <p>Team Reports</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('mechanic.customerVehicles') }}" class="nav-link {{ $isActive(request()->routeIs('mechanic.customerVehicles')) }}">
                                <i class="nav-icon fas fa-car-side"></i>
                                <p>Customer Vehicles</p>
                            </a>
                        </li>
                    @else
                        <li class="nav-item">
                            <a href="{{ route('customer.vehicles') }}" class="nav-link {{ $isActive(request()->routeIs('customer.vehicles*')) }}">
                                <i class="nav-icon fas fa-car"></i>
                                <p>My Vehicles</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.appointments') }}" class="nav-link {{ $isActive(request()->routeIs('customer.appointments*')) }}">
                                <i class="nav-icon fas fa-calendar-alt"></i>
                                <p>Appointments</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('customer.serviceHistory') }}" class="nav-link {{ $isActive(request()->routeIs('customer.serviceHistory')) }}">
                                <i class="nav-icon fas fa-history"></i>
                                <p>Service History</p>
                            </a>
                        </li>
                    @endif
                </ul>
            </nav>

            @if ($u->isMechanic())
                <div class="vms-sidebar-info-card">
                    <div class="vms-sidebar-info-title"><i class="fas fa-clipboard-list mr-1 text-info"></i> Today's Summary</div>
                    <ul class="vms-sidebar-info-list">
                        <li><span>My Tasks:</span><strong>{{ $todayCounts['my_tasks'] }}</strong></li>
                        <li><span>In Progress:</span><strong>{{ $todayCounts['in_progress'] }}</strong></li>
                        <li><span>Completed:</span><strong>{{ $todayCounts['completed'] }}</strong></li>
                        <li><span>Team Tasks:</span><strong>{{ $todayCounts['team_tasks'] }}</strong></li>
                    </ul>
                </div>
                <div class="vms-sidebar-info-card vms-sidebar-info-card-blue">
                    <div class="vms-sidebar-info-title text-primary"><i class="fas fa-tools mr-1"></i> Senior Mechanic Tools</div>
                    <a href="{{ route('mechanic.teamOverview') }}" class="btn btn-primary btn-sm btn-block">Assign Task to Team</a>
                    <p class="text-muted small mt-2 mb-0">Delegate tasks to mechanics who don't have system logins</p>
                </div>
            @endif
        </div>
    </aside>

    <div class="content-wrapper">
        <section class="content pt-3">
            <div class="container-fluid">
                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0 pl-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @isset($header)
                    <div class="mb-3">{!! $header !!}</div>
                @endisset

                {{ $slot }}
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="vms-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" id="vms-modal-content"></div>
    </div>
</div>

<script src="{{ $refAsset }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ $refAsset }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ $refAsset }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ $refAsset }}/plugins/sweetalert2/sweetalert2.min.js"></script>
<script src="{{ $refAsset }}/plugins/toastr/toastr.min.js"></script>
<script src="{{ $refAsset }}/dist/js/adminlte.min.js"></script>
<script src="/js/modal-loader.js"></script>
<script src="/js/search-suggest.js"></script>
@stack('scripts')
</body>
</html>
