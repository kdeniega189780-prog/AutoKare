<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // AdminLTE uses Bootstrap-based pagination.
        Paginator::useBootstrapFour();

        // Use a relative base path so assets work on any host/port (e.g. artisan serve on :8000).
        View::share('refAsset', '/vehicle-ref');
    }
}
