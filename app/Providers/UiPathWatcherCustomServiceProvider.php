<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\UiPathOrchestratorService;

class UiPathWatcherCustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Library\Services\UiPathOrchestratorService', function($app) {
            return new UiPathOrchestratorService();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
