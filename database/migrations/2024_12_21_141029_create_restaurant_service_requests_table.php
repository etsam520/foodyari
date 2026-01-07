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
        Schema::create('restaurant_service_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants');
            $table->string('image')->nullable();
            $table->string('pdf')->nullable();
            $table->string('excel')->nullable();
            $table->string('attachement')->nullable();
            $table->string('restaurant_remarks')->nullable();
            $table->string('admin_remarks')->nullable();
            $table->enum('status', ['pending','approve','reject']);
            $table->timestamp('pending')->nullable();
            $table->timestamp('approve')->nullable();
            $table->timestamp('reject')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_service_requests');
    }
};
