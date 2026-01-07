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
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn([
                'minimum_shipping_charge',
                'per_km_shipping_charge',
                'maximum_shipping_charge',
                'increased_delivery_fee',
                'increased_delivery_fee_status',
                'delivery_range',
                'enble_ext_distance',
                'increase_delivery_charge_message'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->double('minimum_shipping_charge',16, 3, true)->nullable();
            $table->double('per_km_shipping_charge',16, 3, true)->nullable();
            $table->double('maximum_shipping_charge', 23, 3)->nullable();
            $table->double('increased_delivery_fee',8,2)->default('0');
            $table->boolean('increased_delivery_fee_status')->default('0');
            $table->decimal('delivery_range',8,2)->nullable();
            $table->boolean('enble_ext_distance')->default(0);
            $table->string('increase_delivery_charge_message')->nullable();
        });
    }
};
