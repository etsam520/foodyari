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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->decimal('order_amount',$precision = 24, $scale = 2)->default(0);
            $table->decimal('coupon_discount_amount',$precision = 24, $scale = 2)->default(0);
            $table->json('coupon_discount_details')->nullable();
            $table->string('payment_status')->default('unpaid');
            $table->string('order_status')->default('pending');
            $table->decimal('total_tax_amount',$precision = 24, $scale = 2)->default(0);
            $table->json('tax_details')->nullable();
            $table->string('payment_method',30)->nullable();
            $table->string('transaction_reference',191)->nullable();
            $table->text('delivery_instruction')->nullable();
            $table->text('cooking_instruction')->nullable();
            $table->unsignedBigInteger('delivery_man_id')->nullable();
            $table->text('order_note')->nullable();
            $table->string('order_type')->default('delivery');
            $table->boolean('checked')->default(0);
            $table->unsignedBigInteger('restaurant_id');
            $table->foreignId('subscription_id')->nullable();
            $table->decimal('delivery_charge', $precision = 6, $scale = 2)->default(0);
            $table->json('delivery_charge_details')->nullable();
            $table->string('otp')->nullable();
            $table->timestamp('pending')->nullable();
            $table->timestamp('accepted')->nullable();
            $table->timestamp('confirmed')->nullable();
            $table->timestamp('processing')->nullable();
            $table->timestamp('handover')->nullable();
            $table->timestamp('picked_up')->nullable();
            $table->timestamp('delivered')->nullable();
            $table->timestamp('order_on_way')->nullable();
            $table->timestamp('arrived_at_door')->nullable();
            $table->timestamp('dm_at_restaurant')->nullable();
            $table->timestamp('canceled')->nullable();
            $table->timestamp('refund_requested')->nullable();
            $table->timestamp('refunded')->nullable();
            $table->json('refund_details')->nullable();
            $table->string('callback')->nullable();
            $table->boolean('scheduled')->default(0);
            $table->string('delivery_address')->nullable();
            $table->enum('order_to',['self','loved_one'])->nullable();
            $table->boolean('share_bill')->default(1);
            $table->string('share_token',255)->nullable();
            $table->decimal('restaurant_discount_amount',$precision = 24, $scale = 2)->defaul(0.00);
            $table->decimal('custom_discount',$precision = 24, $scale = 2)->default(0.00);
            $table->json('custom_discount_details')->nullable();
            $table->decimal('adjusment')->default(0);
            $table->foreignId('zone_id')->nullable();
            $table->double('dm_tips', 24, 2)->default(0);
            $table->string('cancellation_reason', 255)->nullable();
            $table->string('canceled_by',50)->nullable();
            $table->string('tax_status',50)->nullable();
            $table->string('discount_on_product_by',50)->default('vendor');
            $table->foreignId('vehicle_id')->nullable();
            $table->timestamp('refund_request_canceled')->nullable();
            $table->string('coupon_created_by',50)->nullable();
            $table->text('cancellation_note')->nullable();
            $table->string('free_delivery_by')->nullable();
            $table->string('processing_time',10)->nullable();
            $table->decimal('cash_to_collect',$precision = 24, $scale = 2)->default(0);
            $table->boolean('edited')->default(0);
            $table->decimal('original_delivery_charge', $precision = 8, $scale = 2)->default(0);
            $table->timestamp('failed')->nullable();
            $table->timestamp('schedule_at')->nullable();
            $table->double('distance', 23, 3)->default(0)->nullable();
            $table->foreignId('review_id')->nullable();
            $table->timestamps();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
        });
    }
// cancellation_note
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
