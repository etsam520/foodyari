c<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('zone_delivery_charge_settings', function (Blueprint $table) {
            // Drop the problematic unique constraint
            $table->dropUnique(['zone_id', 'is_active']);
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        
        Schema::table('zone_delivery_charge_settings', function (Blueprint $table) {
            // Restore the original unique constraint
            $table->unique(['zone_id', 'is_active']);
        });
    }
};