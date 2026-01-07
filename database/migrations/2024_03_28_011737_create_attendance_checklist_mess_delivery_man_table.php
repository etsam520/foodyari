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
        Schema::create('attendace_checklist_delivery_man', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attendance_checklist_id');
            $table->unsignedBigInteger('delivery_man_id');
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('tiffin_id');
            $table->enum('status',['initiated','running','delivered'])->nullable();
            $table->integer('cash_to_collect')->default(0);
            $table->timestamps();

            $table->foreign('attendance_checklist_id')->references('id')->on('attendace_check_lists')->onDelete('cascade');
            $table->foreign('delivery_man_id')->references('id')->on('delivery_men')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendace_checklist_delivery_man');
    }
};
