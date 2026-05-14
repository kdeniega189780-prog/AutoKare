@extends('layouts.public')

@section('content')
    <div class="vms-public-section vms-public-section-light vms-about-page">
        <div class="container">
            <div class="vms-glass-panel mx-auto" style="max-width: 960px;">
                <h1 class="h2 font-weight-bold text-center text-primary mb-3">{{ __('About') }} {{ config('app.name') }}</h1>
                <p class="text-center text-white-50 mb-4 mx-auto" style="max-width: 42rem;">
                    {{ __(':name is a smart vehicle maintenance system designed to organize service schedules, monitor maintenance records, and improve fleet management efficiency.', ['name' => config('app.name')]) }}
                </p>
                <div class="row">
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="vms-feature-card">
                            <div class="vms-feature-card-icon" aria-hidden="true"><i class="fas fa-calendar-check"></i></div>
                            <div class="vms-feature-card-title">{{ __('Smart Scheduling') }}</div>
                            <p class="small text-white-50 mb-0">{{ __('Easily schedule maintenance tasks and never miss a service date.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0">
                        <div class="vms-feature-card">
                            <div class="vms-feature-card-icon" aria-hidden="true"><i class="fas fa-clipboard-list"></i></div>
                            <div class="vms-feature-card-title">{{ __('Service Records') }}</div>
                            <p class="small text-white-50 mb-0">{{ __('Keep complete digital records for every vehicle maintenance history.') }}</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="vms-feature-card">
                            <div class="vms-feature-card-icon" aria-hidden="true"><i class="fas fa-chart-line"></i></div>
                            <div class="vms-feature-card-title">{{ __('Fleet Monitoring') }}</div>
                            <p class="small text-white-50 mb-0">{{ __('Monitor fleet condition and performance using real-time insights.') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
