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
        Schema::create('zone_delivery_charge_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('zone_id')->constrained('zones')->onDelete('cascade');
            
            // Tier configuration - JSON format for flexibility
            $table->json('tiers')->comment('Tier configuration with distance ranges, base charges, per_km rates');
            
            // Environmental weight factors
            $table->decimal('rain_weight', 5, 3)->default(0.20)->comment('Rain factor weight (0-1)');
            $table->decimal('traffic_weight', 5, 3)->default(0.15)->comment('Traffic factor weight (0-1)');
            $table->decimal('night_weight', 5, 3)->default(0.10)->comment('Night factor weight (0-1)');
            
            // Other multipliers
            $table->decimal('surge_multiplier', 5, 2)->default(1.0)->comment('Surge pricing multiplier');
            $table->decimal('location_multiplier', 5, 2)->default(1.0)->comment('Location difficulty multiplier');
            $table->decimal('min_fee', 8, 2)->default(5.0)->comment('Minimum delivery fee');
            
            // Status
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
            
            // Ensure one active setting per zone
            $table->unique(['zone_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zone_delivery_charge_settings');
    }
};