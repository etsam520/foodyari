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
        Schema::create('mess_timings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mess_id');
            $table->text('deivery')->nullable();
            $table->text('dine_in')->nullable();
            $table->foreign('mess_id')->references('id')->on('vendor_messes')->onDelete('cascade');
            // $table->timestamps();
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_timings');
    }
};
