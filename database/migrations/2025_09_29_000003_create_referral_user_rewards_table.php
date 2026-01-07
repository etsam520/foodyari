<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referral_user_rewards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sponsor_id'); // Customer ID
            $table->unsignedBigInteger('user_id'); // Customer ID
            $table->unsignedBigInteger('referral_id'); // Referral relationship ID
            $table->integer('order_limit'); // Number of orders required to unlock this reward
            $table->enum('user_reward_type', ['cashback', 'discount']); // Type of reward for user/beneficiary
            $table->enum('sponsor_reward_type', ['cashback', 'discount']); // Type of reward for sponsor
            $table->enum('user_discount_type', ['flat', 'percentage'])->nullable(); // Only for user discount rewards
            $table->enum('sponsor_discount_type', ['flat', 'percentage'])->nullable(); // Only for sponsor discount rewards
            $table->decimal('user_reward_value', 10, 2); // User reward amount or percentage
            $table->decimal('sponsor_reward_value', 10, 2); // Sponsor reward amount or percentage
            $table->decimal('max_amount', 10, 2)->nullable(); // Maximum amount for percentage discounts
            $table->integer('user_current_orders')->default(0); // Current order count for user
            $table->boolean('is_unlocked')->default(false); // Whether reward is unlocked
            $table->boolean('is_user_claimed')->default(false); // Whether reward is claimed
            $table->boolean('is_sponsor_claimed')->default(false); // Whether reward is claimed
            $table->boolean('is_user_used')->default(false); // Whether user has used the referral
            $table->boolean('is_sponsor_used')->default(false); // Whether sponsor has used the referral
            $table->timestamp('unlocked_at')->nullable();
            $table->timestamp('user_claimed_at')->nullable();
            $table->timestamp('sponsor_claimed_at')->nullable();
            $table->timestamp('user_used_at')->nullable();
            $table->timestamp('sponsor_used_at')->nullable();
            $table->unsignedBigInteger('referral_use_id')->nullable(); // Link to specific referral use

            $table->timestamps();

            $table->index(['sponsor_id', 'user_id', 'is_unlocked', 'is_user_claimed'], 'referral_rewards_main_idx');
        });


    }

    public function down()
    {
        Schema::dropIfExists('referral_user_rewards');
    }
};
