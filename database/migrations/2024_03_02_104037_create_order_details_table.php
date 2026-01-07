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
        Schema::create('order_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->nullable();
            $table->foreignId('order_id')->nullable();
            $table->decimal('price',$precision = 24, $scale = 2)->default(0);
            $table->text('food_details')->nullable();
            $table->text('variation')->nullable();
            $table->decimal('discount_on_food',$precision = 24, $scale = 2)->nullable();
            $table->decimal('tax_amount',$precision = 24, $scale = 2)->default(1);
            $table->string('add_on_ids',255)->nullable();
            $table->text('add_ons')->nullable();
            $table->string('discount_type',20)->default('amount');
            $table->decimal('addon_price',$precision = 24, $scale = 2)->default(0.00);
            $table->decimal('variation_price',$precision = 24, $scale = 2)->default(0.00);
            $table->foreignId('item_campaign_id')->nullable();
            $table->integer('quantity')->default(1);

            $table->string('variant',255)->nullable();
            $table->foreignId('review_id')->nullable();
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_details');
    }
};
