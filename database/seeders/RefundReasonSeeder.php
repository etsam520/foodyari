<?php

namespace Database\Seeders;

use App\Models\RefundReason;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RefundReasonSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $customerReasons = [
            'Food quality was poor',
            'Order was incomplete or missing items',
            'Food was cold when delivered',
            'Wrong order delivered',
            'Food taste was not as expected',
            'Delivery took too long',
            'Food was expired or spoiled',
            'Allergic reaction to the food',
            'Restaurant cancelled the order',
            'Delivery person was unprofessional',
            'Food packaging was damaged',
            'Order was delivered to wrong address',
            'Changed my mind about the order',
            'Emergency situation occurred',
            'Duplicate order placed by mistake'
        ];

        $adminReasons = [
            'Technical error in the system',
            'Payment processing issue',
            'Restaurant error',
            'Delivery partner issue',
            'Quality control failure',
            'Customer service resolution',
            'Promotional compensation',
            'App malfunction'
        ];

        $restaurantReasons = [
            'Ingredients not available',
            'Kitchen equipment failure',
            'Staff shortage',
            'Unable to prepare in time',
            'Quality standards not met',
            'Special dietary requirements cannot be met'
        ];

        $deliveryReasons = [
            'Unable to locate customer',
            'Customer unavailable for delivery',
            'Vehicle breakdown',
            'Weather conditions preventing delivery',
            'Order damaged during transport',
            'Safety concerns in delivery area'
        ];

        // Insert customer reasons
        foreach ($customerReasons as $reason) {
            RefundReason::create([
                'reason' => $reason,
                'user_type' => 'customer',
                'status' => true
            ]);
        }

        // Insert admin reasons
        foreach ($adminReasons as $reason) {
            RefundReason::create([
                'reason' => $reason,
                'user_type' => 'admin',
                'status' => true
            ]);
        }

        // Insert restaurant reasons
        foreach ($restaurantReasons as $reason) {
            RefundReason::create([
                'reason' => $reason,
                'user_type' => 'restaurant',
                'status' => true
            ]);
        }

        // Insert delivery man reasons
        foreach ($deliveryReasons as $reason) {
            RefundReason::create([
                'reason' => $reason,
                'user_type' => 'delivery_man',
                'status' => true
            ]);
        }
    }
}
