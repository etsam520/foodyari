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
        Schema::create('mess_services', function (Blueprint $table) {
            $table->id();
            $table->enum('name',['breakfast','lunch','dinner'])->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->boolean('status')->default(1);
            $table->time('available_time_starts')->nullable();
            $table->time('available_time_ends')->nullable();
            $table->timestamps();
        });
    }
      
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_services');
    }
};
