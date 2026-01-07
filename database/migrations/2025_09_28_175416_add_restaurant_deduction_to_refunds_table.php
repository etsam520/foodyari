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
        Schema::table('refunds', function (Blueprint $table) {
            $table->decimal('restaurant_deduction_amount', 24, 2)->default(0)->after('refund_amount');
            $table->text('restaurant_deduction_reason')->nullable()->after('restaurant_deduction_amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropColumn(['restaurant_deduction_amount', 'restaurant_deduction_reason']);
        });
    }
};
