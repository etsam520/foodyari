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
        Schema::create('restaurant_joinee_forms', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            $table->string('restaurant_name');
            $table->string('restaurant_address');
            $table->string('restaurant_phone');
            $table->string('restaurant_email');
            $table->string('restaurant_owner_name');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('restaurant_id')->nullable()->constrained('restaurants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_joinee_forms');
    }
};
