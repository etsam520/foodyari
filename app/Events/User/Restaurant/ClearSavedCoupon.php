<?php

namespace App\Events\User\Restaurant;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ClearSavedCoupon
{
    use Dispatchable, SerializesModels;

    public $customer_id;
    public $clear_if_clear_within_passed;

    /**
     * Create a new event instance.
     */
    public function __construct(int $customer_id,bool $clear_if_clear_within_passed = false)
    {
        $this->customer_id = $customer_id;
        $this->clear_if_clear_within_passed = $clear_if_clear_within_passed;
    }
}
