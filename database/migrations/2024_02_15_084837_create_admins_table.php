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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('f_name',100)->nullable();
            $table->string('l_name',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('email',100)->unique();
            $table->string('image',100)->nullable();
            $table->string('password',100);
            $table->string('fcm_token')->nullable();
            $table->unsignedInteger('otp')->nullable();
            $table->foreignId('role_id')->nullable();
            $table->foreignId('zone_id')->nullable();

            $table->rememberToken();
            $table->timestamp('remember_token_created_at')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
