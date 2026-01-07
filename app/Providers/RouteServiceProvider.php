<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The path to your application's "home" route.
     *
     * Typically, users are redirected here after authentication.
     *
     * @var string
     */
    public const HOME = '/home';

    /**
     * Define your route model bindings, pattern filters, and other route configuration.
     */
    public function boot(): void
    {
         $this->configureRateLimiting();

        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        $this->routes(function () {
            Route::middleware('api')
                ->prefix('api')
                ->group(base_path('routes/api.php'));

            Route::middleware('web')
                ->group(base_path('routes/web.php'));

            Route::prefix('admin')
                ->middleware('web')
                ->group(function () {
                    require base_path('routes/admin.php');
                });

            Route::prefix('restaurant-panel')
                ->middleware('web')
                ->group(base_path('routes/vendor.php'));

            Route::prefix('mess')
                ->middleware('web')
                ->group(base_path('routes/mess.php'));

            Route::prefix('vendor-owner')
                ->middleware('web')
                ->group(base_path('routes/vendorowner.php'));

            Route::prefix('payments')
                ->middleware('web')
                ->group(base_path('routes/Api/payments.php'));

            Route::prefix('deliveryman')
            ->middleware('web')
            ->group(base_path('routes/deliveryman.php'));
        });

    }

    protected function configureRateLimiting()
    {
        RateLimiter::for('location-update-throttle', function ($request) {
            Log::info('Rate Limit Check', ['ip' => $request->ip()]);
            return Limit::perMinutes(0.1667, 1)->by($request->ip());  // 1 request every 10 seconds per IP
        });
    }
}
