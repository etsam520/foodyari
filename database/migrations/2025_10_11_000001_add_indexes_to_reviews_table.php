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
        Schema::table('reviews', function (Blueprint $table) {
            // Add composite index for better performance on order_id and review_to queries
            $table->index(['order_id', 'review_to']);
            
            // Add index for created_at for sorting
            $table->index('created_at');
            
            // Add index for customer_id for joining
            $table->index('customer_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reviews', function (Blueprint $table) {
            $table->dropIndex(['order_id', 'review_to']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['customer_id']);
        });
    }
};
