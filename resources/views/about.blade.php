@extends('layouts.public')

@section('content')
    <header class="vms-public-hero">
        <div class="container d-flex align-items-end h-100 py-5">
            <div class="text-center w-100 pb-4">
                <div class="mx-auto" style="max-width: 52rem;">
                    <h1 class="display-4 font-weight-bold mb-3">{{ __('About') }}</h1>
                    <p class="lead text-white-50 mb-0">{{ __('What AutoKare does for customers, mechanics, and admins.') }}</p>
                </div>
            </div>
        </div>
    </header>

    <section class="vms-public-section vms-public-section-light">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="card card-outline card-primary shadow-sm">
                        <div class="card-body p-4 p-md-5">
                            <h2 class="h3 font-weight-bold mb-3">{{ __('About this system') }}</h2>
                            <p class="text-muted">
                                {{ __('AutoKare supports owners scheduling service, mechanics recording work, and administrators managing users and global reporting.') }}
                            </p>
                            <p class="text-muted">
                                {{ __('Main modules: user management, vehicle management, maintenance scheduling, service execution, and reporting. The interface uses AdminLTE 3 and Bootstrap 4 assets served from public/vehicle-ref.') }}
                            </p>
                            <hr>
                            <p class="small text-muted mb-0">
                                Laravel {{ app()->version() }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
