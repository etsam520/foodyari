<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Throwable;

class BroadcastServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Broadcast::routes();
        // Broadcast::routes();
        // Broadcast::resolveAuthenticatedUserUsing(function ($request) {
        //     foreach (['admin', 'vendor', 'web'] as $guard) {
        //         if (auth($guard)->check()) {
        //             return auth($guard)->user();
        //         }
        //     }

        //     return null; // This causes 403 if no user found
        // });
            require base_path('routes/channels.php');

    }
}
