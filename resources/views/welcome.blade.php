@extends('layouts.public')

@section('content')
    <header class="vms-public-hero vms-public-hero--centered" id="main-header">
        <div class="vms-hero-deco" aria-hidden="true">
            <i class="fas fa-car-side vms-hero-deco-icon"></i>
            <i class="fas fa-oil-can vms-hero-deco-icon"></i>
            <i class="fas fa-cog vms-hero-deco-icon"></i>
        </div>
        <div class="container">
            <div class="vms-hero-card text-center">
                <div class="mb-4">
                    <span class="vms-brand-icon-lg d-inline-flex" aria-hidden="true"><i class="fas fa-tools"></i></span>
                </div>
                <h1 class="display-4 font-weight-bold mb-3 text-white">{{ __('Vehicle Maintenance System') }}</h1>
                <p class="lead text-white-50 mb-4 mx-auto" style="max-width: 36rem;">{{ __('Schedule maintenance, track service history, and manage your fleet efficiently.') }}</p>
                @guest
                    <div class="d-flex flex-column flex-sm-row align-items-stretch align-items-sm-center justify-content-center">
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 mb-2 mb-sm-0 mr-sm-2 shadow-sm">
                                <i class="fas fa-user-plus mr-2"></i>{{ __('Register') }}
                            </a>
                        @endif
                        <a href="{{ route('login') }}" class="btn btn-lg vms-btn-outline-accent px-4">
                            <i class="fas fa-sign-in-alt mr-2"></i>{{ __('Log in') }}
                        </a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 shadow-sm">
                        <i class="fas fa-tachometer-alt mr-2"></i>{{ __('Dashboard') }}
                    </a>
                @endguest
            </div>
        </div>
    </header>
@endsection
