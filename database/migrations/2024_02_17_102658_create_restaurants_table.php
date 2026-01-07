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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('restaurant_no')->nullable();
            $table->string('name');
            $table->string('phone',20)->unique();
            $table->string('email',100)->nullable();
            $table->enum('type', ['veg','non veg','both'])->nullable();
            $table->string('logo')->nullable();
            $table->string('cover_photo')->nullable();
            $table->string('radius')->nullable();
            $table->text('address')->nullable();
            $table->decimal('minimum_order', $precision = 6, $scale = 2)->default(0);
            $table->decimal('delivery_charge', $precision = 6, $scale = 2)->default(0);
            $table->decimal('comission', $precision = 6, $scale = 2)->default(0);
            $table->string('currency',100)->default('INR');
            $table->text('badges')->nullable();
            $table->string('description', 100)->nullable();
            $table->string('tax')->nullable();
            $table->time('opening_time')->nullable();
            $table->time('closeing_time')->nullable();
            $table->time('delivery_time')->nullable();
            $table->time('position')->nullable();
            $table->boolean('self_delivery_system')->default(1);
            $table->boolean('pos_system')->default(1);

            $table->boolean('cash_on_delivery')->default(0);
            $table->boolean('status')->default(1);
            $table->boolean('temp_close')->default(1);
            $table->boolean('ready_to_handover')->default(1);
            $table->foreignId('vendor_id');
            $table->text('off_day')->nullable();
            $table->double('minimum_shipping_charge',24,2)->nullable();
            $table->double('maximum_shipping_charge',24,2)->nullable();
            $table->double('per_km_shipping_charge',24,2)->nullable();
            $table->string('gst')->nullable();
            $table->enum('subscription_type', ['commission','subscription'])->nullable();
            $table->foreignId('zone_id')->nullable();
            $table->text('coordinates')->nullable();

            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('fcm_token')->nullable();
            $table->integer('rating')->nullable();
            $table->string('url_slug',150)->nullable();

            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
