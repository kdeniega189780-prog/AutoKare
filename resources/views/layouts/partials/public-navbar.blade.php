<nav class="navbar navbar-expand-lg navbar-dark vms-public-navbar fixed-top" id="topNavBar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center py-2" href="{{ route('home') }}">
            <span class="vms-brand-icon mr-2" aria-hidden="true"><i class="fas fa-tools"></i></span>
            <span class="d-flex flex-column">
                <span class="font-weight-bold lh-sm">{{ config('app.name') }}</span>
                <span class="vms-brand-sub">{{ __('Vehicle maintenance system') }}</span>
            </span>
        </a>
        <button class="navbar-toggler border-vms-accent" type="button" data-toggle="collapse" data-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav ml-lg-auto d-flex flex-column flex-lg-row align-items-stretch align-items-lg-center mt-3 mt-lg-0 pb-3 pb-lg-0">
                <li class="nav-item mb-2 mb-lg-0 mr-lg-2">
                    <a class="nav-link py-lg-2 {{ request()->routeIs('home') ? 'active font-weight-bold' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                </li>
                <li class="nav-item mb-2 mb-lg-0 mr-lg-3">
                    <a class="nav-link py-lg-2 {{ request()->routeIs('about') ? 'active font-weight-bold' : '' }}" href="{{ route('about') }}">{{ __('About') }}</a>
                </li>
                @auth
                    <li class="nav-item mb-2 mb-lg-0">
                        <a class="btn btn-sm btn-primary px-3" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                @else
                    <li class="nav-item mb-2 mb-lg-0 mr-lg-2">
                        <a class="btn btn-sm vms-btn-outline-accent px-3" href="{{ route('login') }}">{{ __('Log in') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item mb-2 mb-lg-0">
                            <a class="btn btn-sm btn-primary px-3" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>
