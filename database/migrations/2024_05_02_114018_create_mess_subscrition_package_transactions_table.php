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
        Schema::create('mess_subscrition_package_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('customer_id');
            $table->foreignId('mess_id');
            $table->foreignId('product_id');
            $table->enum('state',['enable','desable']);
            $table->enum('meal_collection_type',['delivery', 'dine_in']);
            $table->foreignId('payment_details_id');
            $table->text('special_note')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('coordinates')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_subscrition_package_transactions');
    }
};
