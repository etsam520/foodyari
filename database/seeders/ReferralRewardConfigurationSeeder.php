<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ReferralRewardConfiguration;

class ReferralRewardConfigurationSeeder extends Seeder
{
    public function run()
    {
        // Clear existing configurations
        ReferralRewardConfiguration::truncate();

        // Create sample configurations
        $configurations = [
            [
                'order_count' => 1,
                'user_reward_type' => 'discount',
                'sponsor_reward_type' => 'cashback',
                'user_discount_type' => 'percentage',
                'user_reward_value' => 10.00, // 10% discount for user
                'sponsor_reward_value' => 50.00, // ₹50 cashback for sponsor
                'max_amount' => 100.00,
                'is_active' => true,
            ],
            [
                'order_count' => 3,
                'user_reward_type' => 'cashback',
                'sponsor_reward_type' => 'cashback',
                'user_discount_type' => null,
                'user_reward_value' => 75.00, // ₹75 cashback for user
                'sponsor_reward_value' => 100.00, // ₹100 cashback for sponsor
                'max_amount' => null,
                'is_active' => true,
            ],
            [
                'order_count' => 5,
                'user_reward_type' => 'discount',
                'sponsor_reward_type' => 'cashback',
                'user_discount_type' => 'flat',
                'user_reward_value' => 150.00, // ₹150 flat discount for user
                'sponsor_reward_value' => 200.00, // ₹200 cashback for sponsor
                'max_amount' => null,
                'is_active' => true,
            ],
            [
                'order_count' => 10,
                'user_reward_type' => 'cashback',
                'sponsor_reward_type' => 'cashback',
                'user_discount_type' => null,
                'user_reward_value' => 300.00, // ₹300 cashback for user
                'sponsor_reward_value' => 500.00, // ₹500 cashback for sponsor
                'max_amount' => null,
                'is_active' => true,
            ]
        ];

        foreach ($configurations as $config) {
            ReferralRewardConfiguration::create($config);
        }

        $this->command->info('Referral reward configurations seeded successfully!');
    }
}
