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
        Schema::create('payment_requests', function (Blueprint $table) {
            $table->id();
            $table->decimal('amount',$precision = 24, $scale = 2)->default(0);
            $table->decimal('amount_paid',$precision = 24, $scale = 2)->default(0);
            $table->string('payment_status')->default('unpaid');
            $table->string('payment_method',50)->nullable();
            $table->string('attachment')->nullable();
            $table->string('transaction_reference',191)->nullable();
            $table->text('payments_note')->nullable();
            $table->unsignedBigInteger('delivery_man_id')->nullable();
            $table->unsignedBigInteger('restaurant_id')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->foreignId('vendor_id')->nullable();
            $table->timestamp('pending')->nullable();
            $table->timestamp('accepted')->nullable();
            $table->timestamp('complete')->nullable();
            $table->timestamp('processing')->nullable();
            $table->string('txn_id')->nullable();
            $table->string('remarks')->nullable();
            $table->string('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_requests');
    }
};
