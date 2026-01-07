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
        Schema::create('marquees', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->foreignId('zone_id');
            $table->enum('type', ['restaurant','mess','location','zone']);
            $table->foreignId('restaurant_id')->nullable();
            $table->foreignId('food_id')->nullable();
            $table->foreignId('subscription_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->string('link')->nullable();
            $table->string('file')->nullable();
            $table->boolean('status')->default(true);
            $table->string('data')->nullable();
            $table->string('screen_to')->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->integer('radius')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marquees');
    }
};
