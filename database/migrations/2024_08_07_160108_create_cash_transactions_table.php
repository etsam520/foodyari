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
        Schema::create('cash_transactions', function (Blueprint $table) {
            $table->id();
            $table->double('amount',8,2)->default(0);
            $table->enum('txn_type',['received','paid']);
            $table->enum('received_from',['customer','vendor','deliveryman']);
            $table->enum('paid_to',['customer','vendor','deliveryman']);
            $table->foreignId('wallet_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('admin_fund_id')->nullable();
            $table->foreignId('deliveryman_cash_in_hand_id')->nullable();
            $table->foreignId('vendor_cash_in_hand_id')->nullable();
            $table->text('remarks')->nullable();
            $table->text('deteails')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cash_transactions');
    }
};
