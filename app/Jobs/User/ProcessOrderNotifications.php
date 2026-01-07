<?php

namespace App\Jobs\User;

use App\CentralLogics\Restaurant\BillingForCustomer;
use App\Models\BusinessSetting;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\Order;
use App\Models\Restaurant;
use App\Models\ZoneBusinessSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessOrderNotifications implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public BillingForCustomer $billData;

    public function __construct(Order $order,BillingForCustomer $billData)
    {
        $this->order = $order;
        $this->billData = $billData;
    }

    public function handle()
    {
        try {
            $user = Customer::find($this->billData->userId);

            // Check if this is a scheduled order
            if ($this->order->scheduled == 1 && $this->order->schedule_at) {
                $this->handleScheduledOrder($user);
            } else {
                // Process immediate order notifications
                $this->handleImmediateOrder($user);
            }
        } catch (\Throwable $th) {
            Log::error("Error processing order notifications for order #{$this->order->id}: " . $th->getMessage());
        }
    }

    protected function handleScheduledOrder($user)
    {
        Log::info("Order #{$this->order->id} is scheduled for {$this->order->schedule_at}. Scheduling notifications.");
        
        // Calculate when to send notifications (60 minutes before scheduled time)
        $notificationTime = \Carbon\Carbon::parse($this->order->schedule_at)->subMinutes(60);
        
        // If the notification time is in the past, send immediately
        if ($notificationTime->isPast()) {
            Log::warning("Scheduled notification time is in the past for order #{$this->order->id}. Sending immediately.");
            $this->handleImmediateOrder($user);
            return;
        }
        
        // Schedule the notification job to run 30 minutes before the order time
        ProcessScheduledOrderNotifications::dispatch($this->order, $this->billData)
            ->delay($notificationTime);

        // Send immediate confirmation to customer that order is scheduled
        $this->sendScheduledConfirmationToCustomer($user);
        
        Log::info("Scheduled notifications for order #{$this->order->id} to be sent at {$notificationTime}");
    }

    protected function handleImmediateOrder($user)
    {
        Log::info("Processing immediate order notifications for order #{$this->order->id}");
        
        if ($this->order->order_type == 'delivery') {
            $this->processDeliveryNotifications($user);
        }

        if ($user) {
            $this->sendCustomerNotification($user);
        }
    }

    protected function sendScheduledConfirmationToCustomer($user)
    {
        $notification = [
            'type' => 'Manual',
            'subject' => 'Order Scheduled Successfully',
            'message' => "Your order #{$this->order->id} has been scheduled for {$this->order->schedule_at->format('d M Y, h:i A')}. You will receive preparation notifications 30 minutes before the scheduled time.",
        ];

        SendCustomerNotification::dispatch($user, $notification);
    }

    protected function processDeliveryNotifications($user)
    {
        $deliverymen = DeliveryMan::where('zone_id', $this->billData->zone->id)
            ->where('type', 'admin')
            ->get();

        $notification = $this->prepareDeliveryNotification($user);

        if ($deliverymen->isNotEmpty()) {
            foreach ($deliverymen as $deliveryman) {
                SendDeliveryManNotification::dispatch($deliveryman, $notification);
            }
        }

        $restaurant = Restaurant::find($this->order->restaurant_id);
        SendRestaurantNotification::dispatch($restaurant, $notification);
        SendAdminNotification::dispatch($notification);
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
            'subject' => ZoneBusinessSetting::getSettingValue('dm_order_placed_message', $this->billData->zone->id) ?? 'New Order Available',
            'message' => "Order no  #{$this->order->id}",
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
        ];
    }

    protected function sendCustomerNotification($user)
    {
        $notification = [
            'type' => 'Manual',
            'subject' => ZoneBusinessSetting::getSettingValue('customer_order_place_message', $this->billData->restaurant->zone_id) ?? 'Order Placed Successfully',
            'message' => "Order no  #{$this->order->id}",
        ];

        SendCustomerNotification::dispatch($user, $notification);
    }
}
