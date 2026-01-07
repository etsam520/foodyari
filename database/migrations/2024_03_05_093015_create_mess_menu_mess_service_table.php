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
        Schema::create('mess_menu_mess_service', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mess_menu_id');
            $table->unsignedBigInteger('mess_service_id');
            $table->timestamps();
        
            $table->foreign('mess_menu_id')->references('id')->on('mess_menus')->onDelete('cascade');
            $table->foreign('mess_service_id')->references('id')->on('mess_services')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_menu_mess_service');
    }
};
