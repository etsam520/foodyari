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
        Schema::create('attendace_check_lists', function (Blueprint $table) {
            $table->id();
            $table->time('attendance_time')->default(date("H:i:s"));
            $table->date('attendance_date')->default(date("Y-m-d"));
            $table->enum('service',['breakfast','lunch','dinner'])->nullable();
            $table->text('addons')->nullable();
            $table->foreignId('coupon_id');
            $table->integer('quantity')->default(1); 
            $table->foreignId('attendace_id'); 
            $table->foreignId('mess_id'); 
            $table->boolean('checked')->default(1);
            $table->boolean('sign_to_delivery')->default(0);
            $table->timestamps();
        });
    }
      
  
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendace_check_lists');
    }
};
