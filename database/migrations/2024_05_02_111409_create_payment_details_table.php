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
        Schema::create('payment_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('subscription_order_id')->nullable();
            $table->foreignId('payment_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->decimal('subtotal',8,2)->default(0.00);
            $table->decimal('total',8,2)->default(0.00);
            $table->decimal('tax',8,2)->default(0.00);
            $table->decimal('coupon_discount',8,2)->default(0.00);
            $table->decimal('platform_charge',8,2)->default(0.00);
            $table->decimal('custom_discount',8,2)->default(0.00);
            $table->decimal('discount',8,2)->default(0.00);
            $table->decimal('cash_to_collect',8,2)->default(0.00);
            $table->json('other_charges')->nullable();
            $table->decimal('delivery_charge',8,2)->default(0.00);
            $table->enum('status',['paid','unpaid']);
            $table->string('method')->nullable();
            $table->decimal('wallet',8,2)->default(0.00);
            $table->decimal('cash',8,2)->default(0.00);
            $table->decimal('online',8,2)->default(0.00);
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_details');
    }
};
