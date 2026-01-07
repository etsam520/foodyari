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
        Schema::create('customer_diet_coupon', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('diet_coupon_id');
            $table->string('coupon_no',150)->unique();
            $table->enum('state',['active','reedemed','expired','carried forward'])->default('active');
            $table->timestamps();
        
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('diet_coupon_id')->references('id')->on('diet_coupons')->onDelete('cascade');
        });
    }
       
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_diet_coupon');
    }
};
