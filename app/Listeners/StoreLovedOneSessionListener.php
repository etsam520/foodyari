<?php

namespace App\Listeners;

use App\Events\OurLovedOneSessionStore;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class StoreLovedOneSessionListener
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
    public function handle(OurLovedOneSessionStore $event): void
    {
        $ordSession = $event->session;
        if(isset($ordSession['loved_one_data']) && isset($ordSession['loved_one_data']['name']) && isset($ordSession['loved_one_data']['phone'])){
            \App\Models\LovedOneWithOrder::create([
                'order_id' => $event->order->id,
                'customer_id' => $event->order->customer_id,
                'name' => isset($ordSession['loved_one_data']['name']) ? $ordSession['loved_one_data']['name'] : null,
                'phone' => isset($ordSession['loved_one_data']['phone']) ? $ordSession['loved_one_data']['phone'] : null,
            ]);
        }
    }
}
