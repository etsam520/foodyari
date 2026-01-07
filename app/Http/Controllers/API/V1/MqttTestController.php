<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\MqttService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class MqttTestController extends Controller
{
    protected $mqttService;

    public function __construct(MqttService $mqttService)
    {
        $this->mqttService = $mqttService;
    }

    /**
     * Test MQTT by publishing a dummy order
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testOrder(Request $request)
    {
        try {
            $topic = $request->input('topic', 'orders/new');
            $count = $request->input('count', 1);

            $publishedOrders = [];

            for ($i = 0; $i < $count; $i++) {
                $orderData = $this->generateDummyOrder();
                $message = json_encode($orderData);
                
                $this->mqttService->publish($topic, $message, 1);
                $publishedOrders[] = $orderData;
            }

            return response()->json([
                'success' => true,
                'message' => "Successfully published {$count} order(s) to MQTT broker",
                'topic' => $topic,
                'orders' => $publishedOrders,
                'timestamp' => now()->toDateTimeString(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish to MQTT broker',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test MQTT with custom order data
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function testCustomOrder(Request $request)
    {
        $request->validate([
            'topic' => 'required|string',
            'order_data' => 'required|array',
        ]);

        try {
            $topic = $request->input('topic');
            $orderData = $request->input('order_data');
            
            $message = json_encode($orderData);
            $this->mqttService->publish($topic, $message, 1);

            return response()->json([
                'success' => true,
                'message' => 'Successfully published custom order to MQTT broker',
                'topic' => $topic,
                'order' => $orderData,
                'timestamp' => now()->toDateTimeString(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to publish to MQTT broker',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Test MQTT connection
     * 
     * @return \Illuminate\Http\JsonResponse
     */
    public function testConnection()
    {
        try {
            $testMessage = json_encode([
                'test' => true,
                'message' => 'MQTT connection test',
                'timestamp' => now()->toDateTimeString(),
            ]);

            $this->mqttService->publish('test/connection', $testMessage, 0);

            return response()->json([
                'success' => true,
                'message' => 'MQTT connection is working',
                'broker' => 'mqtt.givni.in:1883',
                'timestamp' => now()->toDateTimeString(),
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'MQTT connection failed',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Generate dummy order data
     */
    private function generateDummyOrder()
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
