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
        Schema::table('zone_delivery_charge_settings', function (Blueprint $table) {
            // Environmental factors for manual override (0-1 scale)
            $table->decimal('rain_factor', 3, 2)->nullable()->comment('Manual rain factor override (0-1)');
            $table->decimal('traffic_factor', 3, 2)->nullable()->comment('Manual traffic factor override (0-1)');
            $table->decimal('night_factor', 3, 2)->nullable()->comment('Manual night factor override (0-1)');
            
            // Auto-detection settings
            $table->boolean('auto_detect_night')->default(true)->comment('Auto-detect night factor based on time');
            $table->boolean('auto_detect_traffic')->default(true)->comment('Auto-detect traffic factor based on rush hours');
            $table->boolean('auto_detect_rain')->default(false)->comment('Auto-detect rain factor (requires weather API)');
            
            // Time-based settings for night factor
            $table->time('night_start_time')->default('20:00')->comment('Night time starts (for auto-detection)');
            $table->time('night_end_time')->default('06:00')->comment('Night time ends (for auto-detection)');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zone_delivery_charge_settings', function (Blueprint $table) {
            $table->dropColumn([
                'rain_factor',
                'traffic_factor', 
                'night_factor',
                'auto_detect_night',
                'auto_detect_traffic',
                'auto_detect_rain',
                'night_start_time',
                'night_end_time'
            ]);
        });
    }
};
