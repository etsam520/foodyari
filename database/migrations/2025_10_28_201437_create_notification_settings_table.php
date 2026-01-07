<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('user_type'); // admin, customer, restaurant, delivery_man
            $table->boolean('order_notifications')->default(true);
            $table->boolean('customer_notifications')->default(true);
            $table->boolean('restaurant_notifications')->default(true);
            $table->boolean('delivery_notifications')->default(true);
            $table->boolean('system_notifications')->default(true);
            $table->boolean('email_notifications')->default(true);
            $table->boolean('sound_notifications')->default(true);
            $table->timestamps();
            
            // Ensure one setting per user
            $table->unique(['user_id', 'user_type']);
            
            // Add indexes for better performance
            $table->index(['user_id', 'user_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_settings');
    }
};
