@php
    $u = Auth::user();
@endphp
<aside class="main-sidebar sidebar-light elevation-2" style="background:#fff;">
    <a href="{{ route('dashboard') }}" class="brand-link bg-primary text-sm">
        <img src="{{ $refAsset }}/ref_ui/AdminLTELogo.png" alt="" class="brand-image img-circle elevation-3 opacity-75" style="width: 2.1rem; height: 2.1rem; max-height: unset;">
        <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-sm vms-side-nav" role="menu">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>{{ __('Dashboard') }}</p>
                    </a>
                </li>

                @if ($u->isAdmin())
                    <li class="nav-item">
                        <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-car"></i>
                            <p>{{ __('Vehicles') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>{{ __('Appointments') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.maintenance.index') }}" class="nav-link {{ request()->routeIs('admin.maintenance.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-wrench"></i>
                            <p>{{ __('Maintenance') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('Reports') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users"></i>
                            <p>{{ __('User Management') }}</p>
                        </a>
                    </li>

                    <li class="nav-header mt-2">{{ __('Admin Privileges') }}</li>
                    <li class="nav-item px-2">
                        <div class="p-2 small text-muted" style="border:1px solid rgba(255,255,255,.12); border-radius:6px;">
                            <div class="mb-1">✓ {{ __('Full System Access') }}</div>
                            <div class="mb-1">✓ {{ __('Add vehicles for customers') }}</div>
                            <div class="mb-1">✓ {{ __('Book appointments') }}</div>
                            <div class="mb-1">✓ {{ __('User Management') }}</div>
                            <div>✓ {{ __('View/Edit all records') }}</div>
                        </div>
                    </li>
                @elseif ($u->isMechanic())
                    <li class="nav-item">
                        <a href="{{ route('mechanic.tasks') }}" class="nav-link {{ request()->routeIs('mechanic.tasks') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-clipboard-list"></i>
                            <p>{{ __('My Assigned Tasks') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mechanic.inProgress') }}" class="nav-link {{ request()->routeIs('mechanic.inProgress') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-play"></i>
                            <p>{{ __('In Progress') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mechanic.completed') }}" class="nav-link {{ request()->routeIs('mechanic.completed') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-check"></i>
                            <p>{{ __('Completed') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mechanic.teamOverview') }}" class="nav-link {{ request()->routeIs('mechanic.teamOverview') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-users-cog"></i>
                            <p>{{ __('Team Overview') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('mechanic.teamReports') }}" class="nav-link {{ request()->routeIs('mechanic.teamReports') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-chart-bar"></i>
                            <p>{{ __('Team Reports') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-car"></i>
                            <p>{{ __('Customer Vehicles') }}</p>
                        </a>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('vehicles.index') }}" class="nav-link {{ request()->routeIs('vehicles.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-car"></i>
                            <p>{{ __('My Vehicles') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('schedules.index') }}" class="nav-link {{ request()->routeIs('schedules.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-calendar-alt"></i>
                            <p>{{ __('Appointments') }}</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                            <i class="nav-icon fas fa-file-alt"></i>
                            <p>{{ __('Service History') }}</p>
                        </a>
                    </li>
                @endif
            </ul>
        </nav>
    </div>
</aside>
