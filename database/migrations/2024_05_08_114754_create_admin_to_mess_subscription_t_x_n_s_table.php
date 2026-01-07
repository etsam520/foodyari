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
        Schema::create('admin_to_mess_subscription_t_x_n_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id');
            $table->foreignId('mess_id');
            $table->double('price', 24, 3)->default(0);
            $table->integer('validity')->default(0);
            $table->string('payment_method', 191);
            $table->string('reference', 191)->nullable();
            $table->double('paid_amount',24, 2);
            $table->integer('discount')->default(0);
            $table->json('package_details');
            $table->string('created_by', 50);
            $table->timestamps();
        });
    }
       

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admin_to_mess_subscription_t_x_n_s');
    }
};
