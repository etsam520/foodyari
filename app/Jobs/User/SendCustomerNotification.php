<?php

namespace App\Jobs\User;

use App\CentralLogics\Helpers;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendCustomerNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $customer;
    public $notification;

    public function __construct(Customer $customer, array $notification)
    {
        $this->customer = $customer;
        $this->notification = $notification;
    }

    public function handle()
    {
        try {
            Helpers::sendOrderNotification($this->customer, $this->notification);
        } catch (\Throwable $th) {
            Log::error($th);
        }
    }
}
