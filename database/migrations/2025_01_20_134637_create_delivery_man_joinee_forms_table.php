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
        Schema::create('delivery_man_joinee_forms', function (Blueprint $table) {
            $table->id();
            $table->string('registration_no')->unique();
            $table->string('deliveryman_name');
            $table->string('deliveryman_phone');
            $table->string('deliveryman_email');
            $table->string('deliveryman_address');
            $table->string('bike_number');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('deliveryman_id')->nullable()->constrained('delivery_men')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_man_joinee_forms');
    }
};
