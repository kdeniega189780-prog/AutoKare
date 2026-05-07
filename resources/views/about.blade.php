@extends('layouts.public')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <h1 class="h3 font-weight-bold mb-3">{{ __('About this system') }}</h1>
                        <p class="text-muted">
                            {{ __('This Vehicle Maintenance Management System supports owners scheduling service, mechanics recording work, and administrators managing users and global reporting.') }}
                        </p>
                        <p class="text-muted">
                            {{ __('Main modules: user management, vehicle management, maintenance scheduling, service execution, and reporting. The interface uses AdminLTE 3 and Bootstrap 4 assets from your reference vehicle_service project, served from public/vehicle-ref.') }}
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
@endsection
