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
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('extra_cooking_time')->default(0)->comment('Additional cooking time in minutes')->after('processing_time');
            $table->timestamp('extra_cooking_time_updated_at')->nullable()->comment('When extra cooking time was last updated')->after('extra_cooking_time');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['extra_cooking_time', 'extra_cooking_time_updated_at']);
        });
    }
};
