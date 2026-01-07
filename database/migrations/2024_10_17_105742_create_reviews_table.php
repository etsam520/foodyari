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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('deliveryman_id')->nullable();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->integer('rating');
            $table->text('review');
            $table->enum('review_to',['restaurant','deliveryman'])->nullable();
            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
