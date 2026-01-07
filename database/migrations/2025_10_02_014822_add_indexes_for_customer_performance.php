<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Add indexes for better query performance
            $table->index(['status', 'created_at'], 'idx_customers_status_created');
            $table->index(['loyalty_points'], 'idx_customers_loyalty_points');
            $table->index(['referral_code'], 'idx_customers_referral_code');
            $table->index(['referred_by'], 'idx_customers_referred_by');
        });

        Schema::table('orders', function (Blueprint $table) {
            // Add composite index for customer orders with status
            $table->index(['customer_id', 'order_status', 'created_at'], 'idx_orders_customer_status_created');
        });

        Schema::table('loyalty_point_transactions', function (Blueprint $table) {
            // Add indexes for loyalty transactions
            $table->index(['customer_id', 'type', 'created_at'], 'idx_loyalty_customer_type_created');
        });

        Schema::table('referral_user_rewards', function (Blueprint $table) {
            // Add indexes for referral rewards
            $table->index(['user_id', 'is_unlocked', 'created_at'], 'idx_referral_rewards_user');
            $table->index(['sponsor_id', 'is_unlocked', 'created_at'], 'idx_referral_rewards_sponsor');
        });

        Schema::table('wallets', function (Blueprint $table) {
            // Add index for customer wallet lookups
            $table->index(['customer_id'], 'idx_wallets_customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Safely drop indexes with try-catch to handle non-existent indexes
        $indexesToDrop = [
            'customers' => [
                'idx_customers_status_created',
                'customers_status_created_at_index',
                'idx_customers_loyalty_points',
                'customers_loyalty_points_index',
                'idx_customers_referral_code',
                'customers_referral_code_index',
                'idx_customers_referred_by',
                'customers_referred_by_index'
            ],
            'orders' => [
                'idx_orders_customer_status_created',
                'orders_customer_id_order_status_created_at_index'
            ],
            'loyalty_point_transactions' => [
                'idx_loyalty_customer_type_created',
                'loyalty_point_transactions_customer_id_type_created_at_index'
            ],
            'referral_user_rewards' => [
                'idx_referral_rewards_user',
                'referral_user_rewards_user_id_is_unlocked_created_at_index',
                'idx_referral_rewards_sponsor',
                'referral_user_rewards_sponsor_id_is_unlocked_created_at_index'
            ],
            'wallets' => [
                'idx_wallets_customer_id',
                'wallets_customer_id_index'
            ]
        ];

        foreach ($indexesToDrop as $table => $indexes) {
            foreach ($indexes as $index) {
                try {
                    DB::statement("ALTER TABLE {$table} DROP INDEX {$index}");
                } catch (\Exception $e) {
                    // Index doesn't exist, continue
                }
            }
        }
    }
};
