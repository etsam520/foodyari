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
        Schema::create('mess_charges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('vendor_mess_id');
            $table->integer('GST')->default(0);
            $table->integer('mess_charge')->default(0);
            $table->enum('mess_charge_type', ['fixed','percent'])->default('fixed');
            $table->integer('admin_charge')->default(0);
            $table->enum('admin_charge_type', ['fixed','percent'])->default('fixed');
            $table->integer('delivery_man_charge')->default(0);
            $table->enum('delivery_man_charge_type', ['fixed','percent'])->default('fixed');
            $table->text('other')->nullable();
            $table->foreign('vendor_mess_id')->references('id')->on('vendor_messes')->onDelete('cascade');
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mess_charges');
    }
};
