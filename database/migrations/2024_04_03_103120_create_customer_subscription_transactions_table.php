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
        Schema::create('customer_subscription_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('subscription_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->foreignId('mess_package_txn_id')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('expiry')->nullable();
            $table->double('amount')->default(0);
            $table->boolean('delivery')->default(0);
            $table->text('delivery_address')->nullable();
            $table->text('coordinates')->nullable();
            $table->enum('payment_type',['cash','wallet','online'])->nullable();
            $table->boolean('diet_status')->default(1);
            $table->boolean('payment_status')->default(0);
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('subscription_id')->references('id')->on('subscription')->onDelete('cascade');
        });
    }
    

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_subscription_transactions');
    }
};
