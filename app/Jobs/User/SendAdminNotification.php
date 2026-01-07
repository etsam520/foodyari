<?php

namespace App\Jobs\User;

use App\CentralLogics\Helpers;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendAdminNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $notification;

    public function __construct(array $notification)
    {
        $this->notification = $notification;
    }

    public function handle()
    {
        try {
            Helpers::sendOrderNotification(Helpers::getAdmin(), $this->notification);

            $topic = 'foodyari_givni_order_data_' . Helpers::getAdmin()->id;
            // (new MqttService())->publish($topic, json_encode($this->notification));
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
