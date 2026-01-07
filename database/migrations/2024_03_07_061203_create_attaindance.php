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
        
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->boolean('state')->default(0);
            $table->foreignId('mess_id')->nullable();
            $table->foreignId('subscription_id')->nullable();
            $table->boolean('status')->default(1);
            $table->foreignId('customer_id');
            $table->timestamps();
        });
    }
      

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attaindance');
    }
};
