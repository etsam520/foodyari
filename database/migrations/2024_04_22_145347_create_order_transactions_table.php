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
        Schema::create('order_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');
            $table->foreignId('delivery_man_id')->nullable();
            $table->boolean('free_delivery')->default(0);
            $table->double('order_amount', 24, 2)->default(0);
            $table->decimal('gst_amount',24,2)->default(0);
            $table->integer('gst_percent')->default(0);
            $table->decimal('platform_charge',24,2)->default(0);
            $table->double('dm_tips', 24, 2)->default(0);

            $table->decimal('delivery_charge',24,2)->default(0);
            $table->decimal('packing_charge',24,2)->default(0);
            $table->decimal('restaurant_earning',24,2)->default(0);
            $table->double('restaurant_gst_amount', 24, 2)->default(0);
            $table->double('restaurant_receivable_amount', 24, 2)->default(0);
            $table->double('admin_commission_amount', 24, 2)->default(0);
            $table->double('admin_earning', 24, 2)->default(0);
            $table->double('admin_gst_amount', 24, 2)->default(0);
            $table->double('admin_receivable_amount', 24, 2)->default(0);
            $table->json('customer_data');
            $table->json('restaurant_data');
            $table->json('admin_data');
            $table->string('received_by');
            $table->foreignId('zone_id')->nullable()->index();
            $table->boolean('status')->default(1);
            $table->enum('delivery_service_provider',['admin','vendor'])->default('admin');
            $table->timestamps();
        });
    }



    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_transactions');
    }
};
