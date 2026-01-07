<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ZoneBusinessSettingServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Load the helper functions
        require_once app_path('Helpers/ZoneBusinessSettingHelpers.php');
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