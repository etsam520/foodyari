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
        Schema::create('diet_coupons', function (Blueprint $table) {
            $table->id();
            $table->enum('diet_name', ['breakfast', 'lunch', 'dinner', 'special']);
            $table->string('coupon_no')->nullable();
            $table->enum('speciality',['normal','special'])->default('normal');
            $table->enum('state',['active','expired','pending','redeem'])->nullable();
            $table->foreignId('customer_subscription_txn_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->timestamp('active')->nullable();
            $table->timestamp('expired')->nullable();
            $table->timestamp('pending')->nullable();
            $table->timestamp('redeem')->nullable();
            $table->date('date');
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('diet_coupons');
    }
};
