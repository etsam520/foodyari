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
        Schema::table('customers', function (Blueprint $table) {
            $table->string('password')->nullable()->change();
            $table->string('fcm_token')->nullable()->change();
            $table->string('remember_token')->nullable()->change();            
            $table->string('email_verification_token')->nullable()->change();
            $table->string('image')->nullable()->change();
            $table->string('otp')->nullable()->change();
            $table->string('pricing')->nullable()->change();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            //
        });
    }
};
