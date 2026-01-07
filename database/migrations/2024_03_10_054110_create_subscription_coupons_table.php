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
        Schema::create('subscription', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('images')->nullable();
            $table->boolean('speciality')->default(0);
            $table->enum('type',['veg','non veg','both'])->nullable();
            $table->integer('validity');
            $table->text('diets');
            $table->decimal('discount')->default(0);
            $table->string('discount_type', 20)->default('percent');
            $table->decimal('price')->default(0);
            $table->foreignId('mess_id')->nullable();
            $table->boolean('status')->default(1);
            $table->timestamps();
        });
    }
  
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscription');
    }
};
