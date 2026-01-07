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
        Schema::create('time_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id');
            $table->date('date');
            $table->time('online')->nullable();
            $table->time('offline')->nullable();
            $table->float('working_hour')->default(0);
            $table->foreignId('shift_id')->nullable();
            $table->decimal('working_hour',23, 3)->default(0)->change();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_logs');
    }
};
