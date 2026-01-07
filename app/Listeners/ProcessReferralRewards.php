<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Http\Controllers\User\Restaurant\apparatusReferral\ReferralPostOrderProcess;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessReferralRewards implements ShouldQueue
{
    use InteractsWithQueue;

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
    public function handle(OrderDelivered $event): void
    {
        try {
            $order = $event->order;

            
            // Process referral rewards for the customer who placed the order
            if ($order->customer_id) {
                $referralProcessor = new ReferralPostOrderProcess($order->customer_id);
                $referralProcessor->unlockReward();
                $marked = $referralProcessor->markRewardAsUsed($order->referral_user_reward_id??0, $order->customer_id);

            if ($marked) {
                Log::info('marked reward as used for order ' . $event->order->id);
            }
        }

        } catch (\Exception $e) {
            Log::error('Error processing referral rewards for order ' . $event->order->id . ': ' . $e->getMessage());
        }
    }
}
