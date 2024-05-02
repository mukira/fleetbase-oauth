<?php

namespace Lipagas\FleetbaseOauth;

use Illuminate\Support\ServiceProvider;

class FleetbaseOauthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
        $this->mergeConfigFrom(
            __DIR__.'./../config/services.php', 'services'
        );
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
        $this->loadRoutesFrom(__DIR__.'/routes.php');
    }
}
