<?php

namespace App\Jobs\User;

use App\CentralLogics\Helpers;
use App\Models\DeliveryMan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendDeliveryManNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $deliveryman;
    public $notification;

    public function __construct(DeliveryMan $deliveryman, array $notification)
    {
        $this->deliveryman = $deliveryman;
        $this->notification = $notification;
    }

    public function handle()
    {
        try {
            Helpers::sendOrderNotification($this->deliveryman, $this->notification);

            // If you need MQTT publishing
            $topic = 'foodyari_givni_order_data_' . $this->deliveryman->id;
            // (new MqttService())->publish($topic, json_encode($this->notification));
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
