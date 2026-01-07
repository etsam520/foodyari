<?php

namespace App\Listeners;

use App\Events\User\Restaurant\ClearSavedCoupon;
use Illuminate\Support\Facades\DB;

class ClearSavedCouponListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(ClearSavedCoupon $event): void
    {

        $shouldCheckTime = $event->clear_if_clear_within_passed ;

        $latestSession = DB::table('order_sessions')
            ->where('customer_id', $event->customer_id)

            ->when($shouldCheckTime, function ($query) {
                $query->where('updated_at', '<=', now()->subMinutes(15));
            })
            ->orderByDesc('updated_at')
            ->select('id')
            ->first();
        // dd($latestSession);

        if ($latestSession) {
            DB::table('order_sessions')
                ->where('id', $latestSession->id)
                ->where('is_locked', false)
                ->update(['applied_coupons' => null]);
        }
    }

}
