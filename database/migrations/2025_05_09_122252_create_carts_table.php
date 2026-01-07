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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_locked')->default(false);
            $table->foreignId('customer_id')->nullable()->constrained()->onDelete('cascade');
            $table->uuid('guest_id')->nullable()->index(); // Don't use uniqid() here
            $table->text('cart')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};
