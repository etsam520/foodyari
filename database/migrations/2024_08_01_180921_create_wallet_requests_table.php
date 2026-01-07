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
        Schema::create('wallet_requests', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('amount')->default(0);
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->unsignedBigInteger('mess_id')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->unsignedBigInteger('deliveryman_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->enum('request_to',['admin','restaurant','mess']);
            $table->enum('request_type',['add','withdraw']);
            $table->enum('status', ['pending', 'success','reject']);
            $table->text('remarks')->nullable();
            $table->text('deteails')->nullable();
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('mess_id')->references('id')->on('vendor_messes')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('deliveryman_id')->references('id')->on('delivery_men')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_requests');
    }
};
