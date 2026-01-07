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
        Schema::create('mess_q_r_s', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->nullable();
            $table->bigInteger('attendance_checklist_id')->unsigned()->nullable();
            $table->bigInteger('mess_deliveryman_id')->unsigned()->nullable();
            $table->bigInteger('diet_coupon_id')->unsigned()->nullable();
            $table->foreignId('tiffin_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->timestamp('checked_at')->nullable();
            $table->string('encrypted_code',255);
            $table->integer('otp')->unsigned();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('attendance_checklist_id')->references('id')->on('attendace_check_lists')->onDelete('cascade');
            $table->foreign('mess_deliveryman_id')->references('id')->on('delivery_men')->onDelete('cascade');
            $table->foreign('diet_coupon_id')->references('id')->on('customer_diet_coupon')->onDelete('cascade');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_q_r_s');
    }
};
