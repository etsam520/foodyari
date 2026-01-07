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
        Schema::create('dm_order_processes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dm_id')->constrained('delivery_men')->onDelete('cascade');
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->timestamp('start_time')->nullable();
            $table->timestamp('picked_up_time')->nullable();
            $table->timestamp('end_time')->nullable();
            $table->string('start_langitude')->nullable();
            $table->string('start_longitude')->nullable();
            $table->string('picked_up_langitude')->nullable();
            $table->string('picked_up_longitude')->nullable();
            $table->string('end_langitude')->nullable();
            $table->string('end_longitude')->nullable();
            $table->string('start_address')->nullable();
            $table->string('end_address')->nullable();
            $table->string('avg_distance')->nullable();
            $table->string('actual_distance')->nullable();
            $table->timestamp('deliver_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dm_order_processes');
    }
};
