<?php

namespace App\Jobs\User;

use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\Restaurant;
use App\CentralLogics\Helpers;
use App\Models\ZoneBusinessSetting;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessScheduledOrderNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $billData;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, $billData)
    {
        $this->order = $order;
        $this->billData = $billData;
    }

    /**
     * Execute the job.
     * This job is scheduled to run 30 minutes before the order's scheduled time
     */
    public function handle(): void
    {
        try {
            Log::info("Processing scheduled order notifications for order #{$this->order->id}");
            
            // Refresh order from database to get current state
            $this->order = Order::find($this->order->id);
            
            // Check if order is still scheduled and hasn't been cancelled
            if (!$this->order || $this->order->order_status === 'canceled') {
                Log::info("Order #{$this->order->id} has been cancelled or doesn't exist. Skipping notifications.");
                return;
            }
            
            $user = Customer::find($this->billData->userId);

            // Update order status to pending (ready for processing)
            $this->order->update([
                'pending' => now(),
                'order_status' => 'pending'
            ]);

            if ($this->order->order_type == 'delivery') {
                $this->processDeliveryNotifications($user);
            }

            if ($user) {
                $this->sendCustomerNotification($user);
            }

            // Trigger the OrderPlaced event for broadcasting
            event(new \App\Events\OrderPlaced($this->order));
            
            Log::info("Scheduled notifications sent successfully for order #{$this->order->id}. Order is now active.");
            
        } catch (\Throwable $th) {
            Log::error("Error processing scheduled notifications for order #{$this->order->id}: " . $th->getMessage());
        }
    }

    protected function processDeliveryNotifications($user)
    {
        $deliverymen = DeliveryMan::where('zone_id', $this->billData->restaurant->zone_id)
            ->where('type', 'admin')
            ->get();

        $notification = $this->prepareDeliveryNotification($user);

        if ($deliverymen->isNotEmpty()) {
            foreach ($deliverymen as $deliveryman) {
                Helpers::sendOrderNotification($deliveryman, $notification);
            }
        }

        $restaurant = Restaurant::find($this->order->restaurant_id);
        Helpers::sendOrderNotification($restaurant, $notification);
        Helpers::sendOrderNotification(Helpers::getAdmin(), $notification);
    }

    protected function prepareDeliveryNotification($user)
    {
        $deliveryAddress = json_decode($this->order->delivery_address, true);
        $bgImage = asset('images/restaurant/staticmap.png');

        if (
            isset($deliveryAddress['position']) &&
            isset($deliveryAddress['position']['lat']) &&
            isset($deliveryAddress['position']['lon']) &&
            $this->billData->restaurant->latitude &&
            $this->billData->restaurant->longitude
        ) {

            $bgImage = "https://maps.googleapis.com/maps/api/staticmap?size=300x600" .
                "&markers=color:red|label:P|{$deliveryAddress['position']['lat']},{$deliveryAddress['position']['lon']}" .
                "&markers=color:blue|label:R|{$this->billData->restaurant->latitude},{$this->billData->restaurant->longitude}" .
                "&key=" . env('GOOGLE_MAPS_API_KEY');
        }

        return [
            'type' => 'Manual',
            'image' => '',
            'subject' => 'Scheduled Order Ready - ' . ZoneBusinessSetting::getSettingValue('customer_order_place_message', $this->billData->restaurant->zone_id),
            'message' => "Scheduled Order no #{$this->order->id} is ready for preparation",
            'order_id' => "{$this->order->id}",
            'order_status' => 'pending',
            'audio_link' => asset('sound/order-received.mp3'),
            'delivery_address' => $deliveryAddress['stringAddress'] ?? null,
            'delivery_lat' => $deliveryAddress['position']['lat'] ?? null,
            'delivery_lng' => $deliveryAddress['position']['lon'] ?? null,
            'restaurant_address' => '',
            'restaurant_lat' => $this->billData->restaurant->latitude ?? null,
            'restaurant_lng' => $this->billData->restaurant->longitude ?? null,
            'restaurant_name' => $this->billData->restaurant->name ?? null,
            'customer_name' => $user->f_name . ' ' . $user->l_name ?? 'foodyari user',
            'order_accept_link' => route('deliveryman.admin.order-confirmation', [
                'order_id' => $this->order->id,
                'status' => 'accept'
            ]),
            'order_reject_link' => route('deliveryman.admin.order-confirmation', [
                'order_id' => $this->order->id,
                'status' => 'reject'
            ]),
            'background_image' => $bgImage,
            'scheduled_time' => $this->order->schedule_at->format('d M Y, h:i A'),
        ];
    }

    protected function sendCustomerNotification($user)
    {
        $notification = [
            'type' => 'Manual',
            'subject' => 'Your Scheduled Order is Being Prepared',
            'message' => "Your scheduled order no #{$this->order->id} is now being prepared and will be delivered as scheduled",
        ];

        Helpers::sendOrderNotification($user, $notification);
    }
}
