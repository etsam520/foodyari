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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('customer_id');
            $table->decimal('refund_amount', 24, 2);
            $table->text('refund_reason');
            $table->enum('refund_method', ['wallet', 'original_payment', 'bank_transfer'])->nullable();
            $table->enum('refund_status', ['pending', 'approved', 'rejected', 'processed', 'canceled_by_customer'])->default('pending');
            $table->enum('refund_type', ['full', 'partial'])->default('full');
            $table->unsignedBigInteger('processed_by')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->json('refund_details')->nullable();
            $table->text('admin_note')->nullable();
            $table->text('customer_note')->nullable();
            $table->string('transaction_reference')->nullable();
            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('admins')->onDelete('set null');

            $table->index(['order_id', 'customer_id']);
            $table->index('refund_status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
