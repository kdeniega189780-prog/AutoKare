@php
    $u = Auth::user();
@endphp
<nav class="main-header navbar navbar-expand navbar-dark border border-secondary border-top-0 border-left-0 border-right-0 navbar-light text-sm">
    <ul class="navbar-nav">
        <li class="nav-item d-none d-sm-inline-block">
            <a href="{{ route('dashboard') }}" class="nav-link">
                {{ __('Vehicle Maintenance System') }}
                @php
                    $roleLabel = $u->isAdmin() ? 'ADMIN' : ($u->isMechanic() ? 'SENIOR MECHANIC' : 'CUSTOMER');
                    $roleClass = $u->isAdmin() ? 'badge badge-danger' : ($u->isMechanic() ? 'badge badge-primary' : 'badge badge-secondary');
                @endphp
                <span class="{{ $roleClass }} ml-2" style="font-size: 10px; vertical-align: middle;">{{ $roleLabel }}</span>
            </a>
        </li>
    </ul>
    <ul class="navbar-nav ml-auto">
        <li class="nav-item d-none d-md-flex align-items-center mr-2">
            <div class="text-right">
                <div class="font-weight-bold" style="line-height: 1.1;">{{ $u->name }}</div>
                <div class="text-muted text-xs" style="line-height: 1.1;">{{ $u->role }}</div>
            </div>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i>
                <span class="d-none d-md-inline">{{ $u->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-item-text text-muted text-sm text-uppercase">{{ str_replace('_', ' ', $u->role) }}</span>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('profile.edit') }}"><i class="fas fa-user mr-2"></i>{{ __('Profile') }}</a>
                <form method="POST" action="{{ route('logout') }}" class="mb-0">
                    @csrf
                    <button type="submit" class="dropdown-item"><i class="fas fa-sign-out-alt mr-2"></i>{{ __('Log Out') }}</button>
                </form>
            </div>
        </li>
    </ul>
</nav>
