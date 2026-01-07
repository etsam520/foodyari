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
        Schema::create('discount_coupon_useds', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('discount_coupon_id')->constrained()->onDelete('cascade');
            // $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('discount_coupon_id');
            $table->foreignId('order_id')->nullable();
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupon_useds');
    }
};
