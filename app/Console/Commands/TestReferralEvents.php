<?php

namespace App\Console\Commands;

use App\Events\OrderDelivered;
use App\Models\Order;
use Illuminate\Console\Command;

class TestReferralEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:referral-events {order_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the referral event system by simulating order delivery';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $orderId = $this->argument('order_id');
        
        if ($orderId) {
            $order = Order::find($orderId);
            if (!$order) {
                $this->error("Order with ID {$orderId} not found!");
                return 1;
            }
        } else {
            // Get the latest delivered order for testing
            $order = Order::where('order_status', 'delivered')
                ->whereNotNull('customer_id')
                ->latest()
                ->first();
                
            if (!$order) {
                $this->error("No delivered orders found for testing!");
                return 1;
            }
        }
        
        $this->info("Testing referral event system with Order ID: {$order->id}");
        $this->info("Customer ID: {$order->customer_id}");
        $this->info("Order Status: {$order->order_status}");
        
        // Fire the event
        event(new OrderDelivered($order));
        
        $this->info("OrderDelivered event fired successfully!");
        $this->info("Check the logs for referral processing details.");
        
        return 0;
    }
}
