<?php

namespace App\Providers;

use App\Services\VendingMachine\VendingMachineService;
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
        $this->app->singleton(VendingMachineService::class, function() {
            return new VendingMachineService();
        });
    }
}
