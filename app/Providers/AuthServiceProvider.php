<?php

namespace App\Providers;

use App\Models\MaintenanceSchedule;
use App\Models\Vehicle;
use App\Policies\MaintenanceSchedulePolicy;
use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Vehicle::class => VehiclePolicy::class,
        MaintenanceSchedule::class => MaintenanceSchedulePolicy::class,
    ];
}

