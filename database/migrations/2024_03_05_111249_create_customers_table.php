<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('f_name');
            $table->string('l_name')->nullable();
            $table->string('phone');
            $table->string('email');
            $table->foreignId('role_id')->nullable();
            $table->string('image');
            $table->string('password');
            $table->string('fcm_token');
            $table->string('remember_token');
            $table->timestamp('remember_token_created_at')->nullable();
            $table->string('email_verification_token');
            $table->text('address');//,
            $table->date('dob')->nullable();
            $table->string('merital_status')->nullable();
            $table->string('gender')->nullable();
            $table->date('anniversary')->nullable();

            $table->text('pricing');
            $table->boolean('status')->default(1);
            $table->boolean('is_phone_verified')->default(0);
            $table->string('otp',6);
            $table->timestamp('otp_expiry')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
