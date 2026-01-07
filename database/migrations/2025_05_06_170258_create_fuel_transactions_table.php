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
        Schema::create('fuel_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dm_id');
            $table->enum('type', ['add', 'deduct']);
            $table->decimal('amount', 10, 2); // ₹ amount added or deducted
            $table->decimal('distance', 8, 2)->nullable(); // distance in KM if it's a deduction
            $table->decimal('rate', 5, 2)->nullable();     // ₹ per km if deduction
            $table->string('note')->nullable();
            $table->foreignId('attendance_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fuel_transactions');
    }
};
