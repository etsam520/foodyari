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
        Schema::create('deliveryman_attendances', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_online')->default(false);
            $table->text('location')->nullable();
            $table->timestamp('last_checked')->nullable();
            $table->foreignId('deliveryman_id')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('deliveryman_attendances');
    }
};
