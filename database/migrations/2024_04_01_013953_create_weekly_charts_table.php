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
        Schema::create('weekly_charts', function (Blueprint $table) {
            $table->id();
            $table->enum('week', [1, 2, 3, 4, 5]);
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday']);
            $table->enum('breakfast', ['normal', 'special', 'off'])->default('off');
            $table->enum('lunch', ['normal', 'special', 'off'])->default('off');
            $table->enum('dinner', ['normal', 'special', 'off'])->default('off');
            $table->foreignId('mess_id')->constrained('vendor_messes')->onDelete('cascade');
 
            // $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_charts');
    }
};
