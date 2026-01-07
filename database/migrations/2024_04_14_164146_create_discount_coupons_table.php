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
        Schema::create('discount_coupons', function (Blueprint $table) {

            $table->id();
            $table->string('title',255)->nullable();
            $table->string('code',255)->nullable();
            $table->string('description',255)->nullable();
            $table->date('start_date')->nullable();
            $table->date('expire_date')->nullable();
            $table->decimal('min_purchase', 24, 2)->default(0);
            $table->decimal('max_discount',$precision = 24, $scale = 2)->default(0);
            $table->decimal('discount',$precision = 24, $scale = 2)->default(0);
            $table->string('discount_type',100)->default('percentage');
            $table->string('coupon_type')->default('default');
            $table->string('data')->nullable();
            $table->integer('limit')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->unsignedBigInteger('mess_id')->nullable();
            $table->bigInteger('total_uses')->nullable()->default(0);
            $table->string('created_by',50)->default('admin')->nullable();
            $table->string('customer_id')->default(json_encode(['all']))->nullable();
            $table->string('slug',255)->nullable();
            $table->boolean('enble_ext_distance')->default(0);
            $table->double('delivery_range')->default(0);
            // $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            // $table->foreign('mess_id')->references('id')->on('vendor_messes')->onDelete('cascade');
            $table->timestamps();

        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_coupons');
    }
};
