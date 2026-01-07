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
        Schema::create('banking_details_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('banking_details_id');
            $table->unsignedBigInteger('vendor_id');
            $table->string('action_type'); // 'created', 'updated', 'deleted'
            $table->json('old_data')->nullable(); // Previous values
            $table->json('new_data')->nullable(); // New values
            $table->json('changed_fields')->nullable(); // List of changed fields
            $table->string('changed_by_type')->default('vendor'); // 'vendor', 'admin', 'system'
            $table->unsignedBigInteger('changed_by_id')->nullable(); // ID of user who made the change
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamps();

            // Indexes
            $table->index(['banking_details_id', 'created_at']);
            $table->index(['vendor_id', 'created_at']);
            $table->index('action_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banking_details_histories');
    }
};
