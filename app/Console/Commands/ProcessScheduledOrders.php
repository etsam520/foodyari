<?php

namespace App\Console\Commands;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ProcessScheduledOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:process-scheduled';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process scheduled orders that have reached their scheduled time';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $now = Carbon::now();
        
        // Find orders that are scheduled and check if they have a schedule_at time that has arrived
        $scheduledOrders = Order::where('order_status', 'scheduled')
            ->where('schedule_at', '<=', $now)
            ->get();

        $this->info("Found {$scheduledOrders->count()} scheduled orders ready to be processed.");

        foreach ($scheduledOrders as $order) {
            try {
                // Update order status to pending and set pending time
                $order->update([
                    'order_status' => 'pending',
                    'pending' => $now
                ]);

                // Trigger the OrderPlaced event for real-time processing
                event(new \App\Events\OrderPlaced($order));

                $this->info("Order #{$order->id} has been activated and is now pending.");
                Log::info("Scheduled order #{$order->id} has been activated at scheduled time.");

            } catch (\Exception $e) {
                $this->error("Failed to process scheduled order #{$order->id}: " . $e->getMessage());
                Log::error("Failed to process scheduled order #{$order->id}: " . $e->getMessage());
            }
        }

        $this->info("Finished processing scheduled orders.");
        
        return 0;
    }
}
