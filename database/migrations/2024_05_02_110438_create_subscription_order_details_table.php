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
        Schema::create('subscription_order_details', function (Blueprint $table) {
            $table->id();
            $table->decimal('total',8,2);
            $table->foreignId('payment_details_id');
            $table->foreignId('customer_id');
            $table->enum('status',['confirmed','pending','canceled'])->default('pending');
            $table->enum('meal_collection',['delivery','dine_in'])->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->text('coordinates')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('cancel_reason')->nullable();
            $table->text('special_note')->nullable();
            $table->foreignId('payment_id')->nullable();
            $table->timestamps();
        });
    }
      

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription_order_details');
    }
};
