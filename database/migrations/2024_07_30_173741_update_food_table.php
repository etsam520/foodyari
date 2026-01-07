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
        Schema::table('food', function (Blueprint $table) {
            $table->foreignId('category_id')->nullable()->change();

            // add if not exists
            if (!Schema::hasColumn('food', 'restaurant_menu_id')) {
                $table->foreignId('restaurant_menu_id')->after('category_id');
            }
            if (!Schema::hasColumn('food', 'restaurant_submenu_id')) {
                $table->foreignId('restaurant_submenu_id')->after('restaurant_menu_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            $table->dropColumn('restaurant_menu_id');
            $table->dropColumn('restaurant_submenu_id');
            $table->foreignId('category_id')->change();
        });
    }
};
