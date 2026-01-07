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
            // add if not exists
            if (!Schema::hasColumn('zones', 'platform_charge')) {
                $table->double('platform_charge', 8, 2)->nullable()->after('max_cod_order_amount');
            }
            if (!Schema::hasColumn('zones', 'platform_charge_original')) {
                $table->double('platform_charge_original', 8, 2)->default(0)->after('platform_charge');
            }
            // $table->double('platform_charge',8,2)->nullable()->after('max_cod_order_amount');
            // $table->double('platform_charge_original', 8,2)->default(0)->after('platform_charge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('zones', function (Blueprint $table) {
            $table->dropColumn('platform_charge_original');
            $table->dropColumn('platform_charge');
        });
    }
};
