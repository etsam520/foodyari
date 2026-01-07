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
        Schema::create('order_sessions', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_locked')->default(false);
            $table->foreignId('customer_id');
            $table->double('dm_tips',8,2)->default(0);
            $table->text('loved_one_data')->nullable();
            $table->string('cooking_instruction')->nullable();
            $table->text('delivery_instruction')->nullable();
            $table->text('applied_coupons')->nullable();
            $table->double('pay_from_wallet',8,2)->default(0);
            $table->double('cash_to_collect',8,2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('gateway_data')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_sessions');
    }
};
