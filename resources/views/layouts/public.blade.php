<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('layouts.adminlte.head')
<body class="d-flex flex-column min-vh-100">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-sm" id="topNavBar">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('home') }}">
            <img src="{{ $refAsset }}/ref_ui/AdminLTELogo.png" width="28" height="28" class="d-inline-block mr-2 rounded-circle" alt="">
            {{ config('app.name') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#publicNav" aria-controls="publicNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="publicNav">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('home') ? 'active font-weight-bold' : '' }}" href="{{ route('home') }}">{{ __('Home') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ request()->routeIs('about') ? 'active font-weight-bold' : '' }}" href="{{ route('about') }}">{{ __('About') }}</a>
                </li>
            </ul>
            <ul class="navbar-nav">
                @auth
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Log in') }}</a>
                    </li>
                    @if (Route::has('register'))
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                        </li>
                    @endif
                @endauth
            </ul>
        </div>
    </div>
</nav>

<main class="flex-grow-1" style="padding-top: 4.5rem;">
    @yield('content')
</main>

<footer class="py-4 bg-dark mt-auto">
    <div class="container text-center text-white-50 small">
        <p class="mb-0">&copy; {{ date('Y') }} {{ config('app.name') }}</p>
    </div>
</footer>

<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<script src="{{ $refAsset }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ $refAsset }}/dist/js/adminlte.min.js"></script>
@stack('scripts')
</body>
</html>
