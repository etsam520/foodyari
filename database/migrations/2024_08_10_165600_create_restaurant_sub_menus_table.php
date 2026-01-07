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
        Schema::create('restaurant_sub_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_menu_id')->constrained()->onDelete('cascade');
            $table->unsignedBigInteger('custom_id')->nullable();
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->integer('position')->default(0);
            $table->string('name');
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->json('details')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_sub_menus');
    }
};
