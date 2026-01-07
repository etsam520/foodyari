<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referral_reward_configurations', function (Blueprint $table) {
            $table->id();
            $table->integer('order_limit'); // Number of orders required to unlock this reward
            $table->enum('user_reward_type', ['cashback', 'discount']); // Type of reward for user/beneficiary
            $table->enum('sponsor_reward_type', ['cashback', 'discount']); // Type of reward for sponsor
            $table->enum('user_discount_type', ['flat', 'percentage'])->nullable(); // Only for user discount rewards
            $table->enum('sponsor_discount_type', ['flat', 'percentage'])->nullable(); // Only for sponsor discount rewards
            $table->decimal('user_reward_value', 10, 2); // User reward amount or percentage
            $table->decimal('sponsor_reward_value', 10, 2); // Sponsor reward amount or percentage
            $table->decimal('max_amount', 10, 2)->nullable(); // Maximum amount for percentage discounts
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_reward_configurations');
    }
};
