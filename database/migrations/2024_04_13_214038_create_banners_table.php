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
        Schema::create('banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->enum('type',['food','restaurant','mess','location','zone']);
            $table->string('image')->nullable();
            $table->boolean('status')->default(1);
            $table->string('data')->nullable();
            $table->string('screen_to')->nullable();
            $table->foreignId('restaurant_id')->nullable();
            $table->foreignId('food_id')->nullable();
            $table->foreignId('subscription_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->unsignedBigInteger('zone_id')->nullable();
            $table->string('latitude', 100)->nullable();
            $table->string('longitude', 100)->nullable();
            $table->integer('radius')->nullable();
            $table->string('link')->nullable();
            $table->timestamps();
            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banners');
    }
};
