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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('type')->default('restaurant_kyc');
            $table->string('name');
            $table->boolean('is_required')->default(false);
            $table->boolean('is_text')->default(false);
            $table->boolean('is_text_required')->default(false);
            $table->boolean('is_media')->default(true);
            $table->boolean('is_media_required')->default(false);
            $table->boolean('has_expiry_date')->default(false);
            $table->string('status')->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
