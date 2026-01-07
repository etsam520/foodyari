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
        Schema::create('vendor_messes', function (Blueprint $table) {
            $table->id();
            $table->string('mess_no');
            $table->string('name');
            $table->string('description',500)->nullable();
            $table->string('email')->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('radius')->nullable();
            $table->text('badges')->nullable();
            $table->text('diet_cost')->nullable();
            $table->text('coordinates')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();

            $table->text('address')->nullable();
            $table->decimal('minimum_order', $precision = 6, $scale = 2)->default(0);
            $table->decimal('delivery_charge', $precision = 6, $scale = 2)->default(0);
            $table->decimal('comission', $precision = 6, $scale = 2)->default(0);
            $table->string('currency',100)->default('INR');
            $table->string('tax')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closeing_time')->nullable();
            $table->time('delivery_time')->nullable();
            $table->time('position')->nullable();
            $table->unsignedBigInteger('vendor_id')->uniqid();
            $table->boolean('cash_on_delivery')->default(0);
            $table->boolean('status')->default(1);
            $table->foreignId('zone_id');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vendor_messes');
    }
};
