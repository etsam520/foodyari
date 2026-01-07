<?php

namespace App\Console\Commands;

use App\Services\MqttService;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class TestMqttOrder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mqtt:test-order {--topic=orders/new} {--count=1} {--dry-run : Show data without publishing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test MQTT by publishing dummy order data';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $topic = $this->option('topic');
        $count = (int) $this->option('count');
        $dryRun = $this->option('dry-run');

        $this->info("📡 Starting MQTT Order Test" . ($dryRun ? " (DRY RUN)" : ""));
        $this->info("Topic: {$topic}");
        $this->info("Orders to send: {$count}");
        $this->newLine();

        try {
            if (!$dryRun) {
                $mqttService = new MqttService();
            }
            
            for ($i = 1; $i <= $count; $i++) {
                $orderData = $this->generateDummyOrder($i);
                
                $this->line("🔄 " . ($dryRun ? "Generating" : "Publishing") . " order #{$i}...");
                $this->table(
                    ['Field', 'Value'],
                    [
                        ['Order ID', $orderData['order_id']],
                        ['Customer', $orderData['customer_name']],
                        ['Restaurant', $orderData['restaurant_name']],
                        ['Amount', '$' . $orderData['order_amount']],
                        ['Status', $orderData['order_status']],
                        ['Items', count($orderData['items'])],
                    ]
                );

                if (!$dryRun) {
                    $message = json_encode($orderData);
                    $mqttService->publish($topic, $message, 1);
                    $this->info("✅ Order #{$i} published successfully!");
                } else {
                    $this->info("✅ Order #{$i} generated (not published - dry run)");
                    $this->line("JSON Payload:");
                    $this->line(json_encode($orderData, JSON_PRETTY_PRINT));
                }
                
                $this->newLine();
                
                if ($i < $count) {
                    sleep(1); // Wait 1 second between orders
                }
            }

            if ($dryRun) {
                $this->info("🎉 All {$count} order(s) generated successfully! (Dry run - nothing published)");
            } else {
                $this->info("🎉 All {$count} order(s) published successfully to MQTT broker!");
            }
            return Command::SUCCESS;

        } catch (\Exception $e) {
            $this->error("❌ Error: " . $e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Generate dummy order data
     */
    private function generateDummyOrder($index)
    {
        $customers = ['John Doe', 'Jane Smith', 'Mike Johnson', 'Sarah Williams', 'Tom Brown'];
        $restaurants = ['Pizza Palace', 'Burger House', 'Sushi Bar', 'Taco Town', 'Curry Corner'];
        $statuses = ['pending', 'confirmed', 'processing', 'picked_up'];
        $items = [
            ['name' => 'Margherita Pizza', 'price' => 12.99, 'quantity' => 1],
            ['name' => 'Chicken Burger', 'price' => 9.99, 'quantity' => 2],
            ['name' => 'California Roll', 'price' => 15.99, 'quantity' => 1],
            ['name' => 'Beef Tacos', 'price' => 8.99, 'quantity' => 3],
            ['name' => 'Chicken Curry', 'price' => 13.99, 'quantity' => 1],
        ];

        $selectedItems = array_slice($items, 0, rand(1, 3));
        $totalAmount = array_sum(array_map(fn($item) => $item['price'] * $item['quantity'], $selectedItems));

        return [
            'order_id' => 'ORD-' . strtoupper(Str::random(8)),
            'customer_id' => rand(1, 100),
            'customer_name' => $customers[array_rand($customers)],
            'customer_phone' => '+1' . rand(1000000000, 9999999999),
            'restaurant_id' => rand(1, 50),
            'restaurant_name' => $restaurants[array_rand($restaurants)],
            'order_amount' => round($totalAmount, 2),
            'delivery_charge' => 3.99,
            'total_tax_amount' => round($totalAmount * 0.08, 2),
            'payment_method' => ['cash', 'card', 'digital_wallet'][rand(0, 2)],
            'payment_status' => ['paid', 'unpaid'][rand(0, 1)],
            'order_status' => $statuses[array_rand($statuses)],
            'order_type' => ['delivery', 'takeaway'][rand(0, 1)],
            'delivery_address' => [
                'street' => rand(100, 999) . ' Main Street',
                'city' => 'New York',
                'state' => 'NY',
                'zip' => '100' . rand(10, 99),
            ],
            'items' => $selectedItems,
            'order_note' => 'Please ring the doorbell',
            'cooking_instruction' => 'Extra spicy',
            'scheduled' => false,
            'created_at' => now()->toDateTimeString(),
            'estimated_delivery_time' => now()->addMinutes(30)->toDateTimeString(),
        ];
    }
}
