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
        Schema::create('admin_fund_txns', function (Blueprint $table) {
            $table->id();
            $table->double('amount', 8,2)->default(0);
            $table->foreignId('admin_fund_id');
            $table->enum('txn_type', ['received', 'paid']);
            $table->enum('received_from', ['customer', 'vendor', 'deliveryman'])->nullable();
            $table->enum('paid_to', ['customer', 'vendor', 'deliveryman'])->nullable();
            $table->foreignId('vendor_id')->nullable();
            $table->foreignId('deliveryman_id')->nullable();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('restaurant_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->text('remarks')->nullable();
            $table->text('deteails')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_fund_txns');
    }
};
