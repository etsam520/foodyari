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
        Schema::create('gateway_payments', function (Blueprint $table) {
            $table->id();
            $table->double('amount',24,2);
            $table->enum('payment_status',['pending','success','failed'])->default('pending');
            $table->string('txn_id',255)->nullable();
            $table->string('merchant_txn_id',255)->nullable();
            $table->string('currency')->default('INR');
            $table->string('gateway');
            $table->string('assosiate');
            $table->foreignId('assosiate_id');
            $table->string('responseCode')->nullable();
            $table->json('response')->nullable();
            $table->json('payload')->nullable();
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gateway_payments');
    }
};
