@extends('layouts.public')

@section('content')
    <header class="bg-dark text-white position-relative overflow-hidden" id="main-header" style="min-height: 55vh;">
        <div class="position-absolute w-100 h-100" style="background: radial-gradient(circle, rgba(0,0,0,0.55) 22%, rgba(0,0,0,0.35) 50%, rgba(0,212,255,0) 100%); z-index: 1;"></div>
        <div class="container position-relative d-flex align-items-end h-100 py-5" style="z-index: 2; min-height: 55vh;">
            <div class="text-center w-100 pb-4">
                <h1 class="display-4 font-weight-bold">{{ config('app.name') }}</h1>
                <p class="lead text-white-50 mb-4">{{ __('Schedule maintenance, track service history, and manage your fleet.') }}</p>
                @guest
                    <div>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="btn btn-primary btn-lg rounded-0 mr-2">{{ __('Register') }}</a>
                        @endif
                        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg rounded-0">{{ __('Log in') }}</a>
                    </div>
                @else
                    <a href="{{ route('dashboard') }}" class="btn btn-primary btn-lg rounded-0">{{ __('Dashboard') }}</a>
                @endguest
            </div>
        </div>
    </header>

    <section class="py-5 bg-light">
        <div class="container">
            <div class="row">
                <div class="col-md-5 mb-4">
                    <h3 class="text-center">{{ __('Built for') }}</h3>
                    <hr class="border-primary bg-primary">
                    <ul class="list-group list-group-flush shadow-sm">
                        <li class="list-group-item"><strong>{{ __('Vehicle owners') }}</strong> — {{ __('register vehicles and book service') }}</li>
                        <li class="list-group-item"><strong>{{ __('Mechanics') }}</strong> — {{ __('record parts, labor, and completion') }}</li>
                        <li class="list-group-item"><strong>{{ __('Administrators') }}</strong> — {{ __('manage users and reporting') }}</li>
                    </ul>
                </div>
                <div class="col-md-7">
                    <h3 class="text-center">{{ __('At a glance') }}</h3>
                    <hr class="border-primary bg-primary">
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
