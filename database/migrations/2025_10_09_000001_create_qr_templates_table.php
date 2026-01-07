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
        Schema::create('qr_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('zone_id');
            $table->json('template_data'); // Stores all template configuration
            $table->enum('background_type', ['color', 'image'])->default('color');
            $table->string('background_value')->nullable(); // Color hex or image filename
            $table->boolean('status')->default(true);
            $table->boolean('is_default')->default(false);
            $table->unsignedBigInteger('created_by');
            $table->timestamps();

            $table->foreign('zone_id')->references('id')->on('zones')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('admins')->onDelete('cascade');
            
            $table->index(['zone_id', 'status']);
            $table->index(['zone_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qr_templates');
    }
};
