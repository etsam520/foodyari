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
        Schema::create('users', function (Blueprint $table) {
            $table->string('f_name',100)->nullable();
            $table->string('l_name',100)->nullable();
            $table->string('phone')->nullable();
            $table->string('email',100)->nullable();
            $table->string('image',100)->nullable();
            $table->unsignedBigInteger('role_id')->nullable();
            $table->boolean('is_phone_verified')->default(0);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password',100);
            $table->string('email_verification_token')->nullable();
            $table->string('cm_firebase_token')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
