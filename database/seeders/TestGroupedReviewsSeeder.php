<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use App\Models\Order;
use App\Models\Customer;
use App\Models\DeliveryMan;
use App\Models\Restaurant;

class TestGroupedReviewsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some existing orders, customers, restaurants and delivery men
        $orders = Order::take(3)->get();
        $customers = Customer::take(3)->get();
        $restaurants = Restaurant::take(3)->get();
        $deliverymen = DeliveryMan::take(3)->get();
        
        if ($orders->isEmpty() || $customers->isEmpty() || $restaurants->isEmpty() || $deliverymen->isEmpty()) {
            $this->command->info('Not enough data to create test reviews. Make sure you have orders, customers, restaurants and delivery men in your database.');
            return;
        }
        
        foreach ($orders as $index => $order) {
            $customer = $customers->get($index % $customers->count());
            $restaurant = $restaurants->get($index % $restaurants->count());
            $deliveryman = $deliverymen->get($index % $deliverymen->count());
            
            // Create restaurant review
            Review::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'restaurant_id' => $restaurant->id,
                'rating' => rand(3, 5),
                'review' => 'Test restaurant review for order #' . $order->id . '. The food quality was good and delivery was on time.',
                'review_to' => 'restaurant'
            ]);
            
            // Create deliveryman review
            Review::create([
                'order_id' => $order->id,
                'customer_id' => $customer->id,
                'deliveryman_id' => $deliveryman->id,
                'rating' => rand(3, 5),
                'review' => 'Test deliveryman review for order #' . $order->id . '. The delivery person was polite and professional.',
                'review_to' => 'deliveryman'
            ]);
        }
        
        $this->command->info('Test grouped reviews created successfully!');
    }
}
