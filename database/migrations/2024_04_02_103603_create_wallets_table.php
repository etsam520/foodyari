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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('balance')->default(0);
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('deliveryman_id')->unsigned()->nullable();
            $table->foreignId('vendor_id')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->timestamps();
            $table->foreign('deliveryman_id')->references('id')->on('delivery_men')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
