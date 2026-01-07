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
        Schema::create('weekly_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name',255);
            $table->enum('type',['veg','non_veg','both']);
            $table->text('description')->nullable();
            $table->string('image')->nullable();
            $table->string('addons')->nullable();
            $table->enum('service',['breakfast','lunch','dinner']);
            $table->enum('day',['monday','tuesday','wednesday','thursday','friday','saturday','sunday']);
            $table->unsignedBigInteger('mess_id')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamp('aviliable_time_start')->nullable();
            $table->timestamp('aviliable_time_end')->nullable();
            $table->timestamps();
            $table->foreign('mess_id')->references('id')->on('vendor_messes')->onDelete('cascade');
        });
    } 

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_menus');
    }
};
