<?php

namespace App\Jobs\User;

use App\CentralLogics\Helpers;
use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendRestaurantNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $restaurant;
    public $notification;

    public function __construct(Restaurant $restaurant, array $notification)
    {
        $this->restaurant = $restaurant;
        $this->notification = $notification;
    }

    public function handle()
    {
        try {
            Helpers::sendOrderNotification($this->restaurant, $this->notification);

            // $topic = 'foodyari_givni_order_data_' . $this->restaurant?->id;
            // (new MqttService())->publish($topic, json_encode($this->notification));
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
