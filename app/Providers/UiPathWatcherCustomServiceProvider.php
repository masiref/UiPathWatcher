<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Library\Services\AlertTriggerService;
use App\Library\Services\UiPathOrchestratorService;
use App\Library\Services\ElasticSearchService;

class UiPathWatcherCustomServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('App\Library\Services\AlertTriggerService', function($app) {
            return new AlertTriggerService();
        });

        $this->app->singleton('App\Library\Services\UiPathOrchestratorService', function($app) {
            return new UiPathOrchestratorService();
        });

        $this->app->singleton('App\Library\Services\ElasticSearchService', function($app) {
            return new ElasticSearchService();
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
