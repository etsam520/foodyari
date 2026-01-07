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
        Schema::create('delivery_men', function (Blueprint $table) {
            $table->id();
            $table->enum('application_status', ['approved', 'denied','pending'])->default('approved');
            $table->foreignId('vehicle_id')->nullable();
            $table->foreignId('shift_id')->nullable();
            $table->string('f_name',100);
            $table->string('l_name',100)->nullable();
            $table->string('phone',20)->unique();
            $table->string('email',100)->nullable();
            $table->string('identity_number',30)->nullable();
            $table->string('identity_type',50)->nullable();
            $table->text('identity_image')->nullable();
            $table->string('image',100)->nullable();
            $table->string('password',100);
            $table->string('auth_token')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('remember_token');
            $table->timestamp('remember_token_created_at')->nullable();
            $table->text('address')->nullable();
            $table->enum('type',['restaurant','mess','admin'])->nullable();
            $table->foreignId('zone_id')->nullable();
            $table->foreignId('admin_id')->nullable();
            $table->foreignId('restaurant_id')->nullable();
            $table->foreignId('mess_id')->nullable();
            $table->boolean('status')->default(1);
            $table->boolean('active')->default(1);
            $table->boolean('earning')->default(1);
            $table->double('fuel_rate',8,2)->default(0);
            $table->timestamps();


        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('delivery_men');
    }
};
