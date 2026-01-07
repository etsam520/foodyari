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
        Schema::create('mess_deliveryman_order_accepts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable();
            $table->foreignId('checkList_id')->nullable();
            $table->foreignId('dm_id')->nullable();
            $table->foreignId('mess_qrId')->nullable();
            $table->text('delivery_address')->nullable();
            $table->text('coordinates')->nullable();
            $table->enum('status',['accepted','rejected','pickedUp','delivered'])->nullable();
            $table->timestamp('accepted_at')->nullable();
            $table->timestamps();
        });
    }
      

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_deliveryman_order_accepts');
    }
};
