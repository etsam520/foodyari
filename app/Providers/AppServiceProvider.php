<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use App\Channels\FirebaseChannel;
use App\Models\BusinessSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Schema;
use Kreait\Firebase\Contract\Messaging;


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
        Paginator::useBootstrapFive();

        date_default_timezone_set(config('app.timezone'));

        // Optional: also force Carbon to follow
        \Carbon\Carbon::setLocale(config('app.locale'));

        // Bind FirebaseChannel
        $this->app->bind(FirebaseChannel::class, function ($app) {
            return new FirebaseChannel($app->make(Messaging::class));
        });
    }

}
