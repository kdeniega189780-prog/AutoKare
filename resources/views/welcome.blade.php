@extends('layouts.public')

@section('content')
    <header class="vms-public-hero" id="main-header">
        <div class="container d-flex align-items-end h-100 py-5">
            <div class="text-center w-100 pb-4">
                <div class="mx-auto" style="max-width: 52rem;">
                    <div class="mb-3">
                        <span class="vms-brand-icon-lg" aria-hidden="true"><i class="fas fa-car-side"></i></span>
                    </div>
                    <h1 class="display-4 font-weight-bold mb-3">{{ config('app.name') }}</h1>
                    <p class="lead text-white-50 mb-4">{{ __('Schedule maintenance, track service history, and manage your fleet.') }}</p>
                </div>
                @guest
                    <div>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg px-4 mr-2 shadow-sm">{{ __('Register') }}</a>
                        @endif
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-4">{{ __('Log in') }}</a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg px-4 shadow-sm">{{ __('Dashboard') }}</a>
                @endguest
            </div>
        </div>
    </header>

    <section class="vms-public-section vms-public-section-light">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-4">
                    <h2 class="h4 font-weight-bold text-center mb-2">{{ __('Built for') }}</h2>
                    <div class="mx-auto bg-primary" style="height:3px;width:64px;border-radius:999px;"></div>
                    <div class="mb-4"></div>
                    <ul class="list-group list-group-flush shadow-sm">
                        <li class="list-group-item"><strong>{{ __('Vehicle owners') }}</strong> — {{ __('register vehicles and book service') }}</li>
                        <li class="list-group-item"><strong>{{ __('Mechanics') }}</strong> — {{ __('record parts, labor, and completion') }}</li>
                        <li class="list-group-item"><strong>{{ __('Administrators') }}</strong> — {{ __('manage users and reporting') }}</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <h2 class="h4 font-weight-bold text-center mb-2">{{ __('At a glance') }}</h2>
                    <div class="mx-auto bg-primary" style="height:3px;width:64px;border-radius:999px;"></div>
                    <div class="mb-4"></div>
                    <p class="text-muted">
                        {{ __('This application keeps the same Laravel backend you already have—vehicles, maintenance schedules, service records, role-based access, and CSV reports—while presenting the interface in the same AdminLTE / Bootstrap style as the reference vehicle service project.') }}
                    </p>
                    <p class="mb-0">
                        <a href="{{ route('about') }}" class="font-weight-bold text-primary">{{ __('Read more on the About page') }}</a>
                    </p>
                </div>
            </div>
        </div>
    </section>
@endsection
