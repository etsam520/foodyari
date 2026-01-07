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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('image', 30)->nullable();
            $table->foreignId('category_id')->nullable();
            $table->foreignId('restaurant_menu_id')->nullable();
            $table->foreignId('restaurant_submenu_id')->nullable();
            $table->string('category_ids', 255)->nullable();
            $table->boolean('isCustomize')->default(false);
            $table->boolean('isRecommended')->default(false);
            $table->text('variations')->nullable();
            $table->string('add_ons')->nullable();
            $table->string('attributes', 255)->nullable();
            $table->text('choice_options')->nullable();
            $table->decimal('price', 8, 2)->default(0);
            $table->decimal('restaurant_price', 8, 2)->default(0);
            $table->decimal('admin_margin', 8, 2)->default(0);
            $table->decimal('discount', 8, 2)->default(0);
            $table->enum('discount_by', ['admin', 'restaurant'])->default('admin');
            $table->time('available_time_starts')->nullable();
            $table->time('available_time_ends')->nullable();
            $table->boolean('set_menu')->default(1);
            $table->enum('type', ['veg', 'non veg', 'both'])->nullable();
            $table->boolean('status')->default(1);
            $table->text('position')->nullable();
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('food', function (Blueprint $table) {
            // Drop the foreign key constraints before dropping the table
            $table->dropForeign(['category_id']);
            $table->dropForeign(['restaurant_id']);
        });

        Schema::dropIfExists('food');
    }
};
