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
        Schema::create('mess_food_processings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mess_id')->nullable();
            $table->enum('service',['breakfast','lunch','dinner'])->nullable();
            $table->enum('speciality',['normal','special'])->nullable();
            $table->enum('steps',['processed','readyToDeliver','delivered'])->nullable();
            $table->text('data')->nullable();
            $table->integer('dine_in')->nullable();
            $table->integer('delivery')->nullable();
            $table->timestamps();
        });
    }
      

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_food_processings');
    }
};
