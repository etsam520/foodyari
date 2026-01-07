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
        Schema::create('zones', function (Blueprint $table) {
                $table->id();
                $table->string('name')->unique();
                $table->text('coordinates')->nullable();
                $table->float('radius')->nullable();
                $table->boolean('status')->default(1);
                $table->boolean('isTopOrders')->default(0);
                $table->string('restaurant_wise_topic')->nullable();
                $table->string('customer_wise_topic')->nullable();
                $table->string('deliveryman_wise_topic')->nullable();

                $table->double('max_cod_order_amount', 23, 3)->nullable();

                $table->double('platform_charge', 16, 3)->nullable();
                $table->double('platform_charge_original', 16, 3)->nullable();
                $table->double('min_purchase', 16, 3)->nullable();

                $table->boolean('delivery_verification')->default('0');

                $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zones');
    }
};
