<?php

namespace App\Listeners;

use App\Events\OrderDelivered;
use App\Services\LoyaltyPointService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class ProcessLoyaltyPoints implements ShouldQueue
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

            // Award loyalty points for completed order if customer exists
            $result = LoyaltyPointService::awardPointsOnOrderCompletion($order);
            
            if ($result) {
                Log::info("Loyalty points awarded for order #{$order->id} for customer #{$order->customer_id}");
            } else {
                Log::warning("Failed to award loyalty points for order #{$order->id}");
            }

        } catch (\Exception $e) {
            Log::error('Error processing loyalty points: ' . $e->getMessage());
        }
    }
}
