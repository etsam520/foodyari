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
        Schema::create('mess_tiffins', function (Blueprint $table) {
            $table->id();
            $table->string('no')->unique();
            $table->string('title')->nullable();
            $table->bigInteger('mess_id')->unsigned();
            $table->foreign('mess_id')->references('id')->on('vendor_messes')->onDelete('cascade');  
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_tiffins');
    }
};
