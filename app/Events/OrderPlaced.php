<?php

namespace App\Events;

use App\Models\Order;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class OrderPlaced  implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }
    // event(new OrderPlaced($order));

    public function broadcastOn()
    {
        return [
            new PrivateChannel('admin'),
            new PrivateChannel('deliveryman.' . 6),
            new PrivateChannel('vendor.' . 12),
            // new PrivateChannel('user.' . $this->order->restaurant->user_id),
        ];
    }

    public function broadcastAs()
    {
        return 'order.placed';
    }

    public function broadcastWith()
    {
        return [
            'order_id' => $this->order->id,
            'instructions' => "dlkfjdkl",
            'amount' => 100,
            'placed_at' => now()->toTimeString(),
        ];
    }
}
