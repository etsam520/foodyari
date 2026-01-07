<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\User\Restaurant\ClearSavedCoupon::class => [
            \App\Listeners\ClearSavedCouponListener::class,
        ],
        \App\Events\OrderDelivered::class => [
            \App\Listeners\ProcessReferralRewards::class,
            \App\Listeners\ProcessLoyaltyPoints::class,
        ],

        \App\Events\OurLovedOneSessionStore::class => [
            \App\Listeners\StoreLovedOneSessionListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}
