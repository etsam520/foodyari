<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referral_uses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('referral_id');
            $table->unsignedBigInteger('beneficiary_id');
            $table->timestamp('used_at');
            $table->timestamps();

            $table->foreign('referral_id')->references('id')->on('referrals')->onDelete('cascade');
            $table->foreign('beneficiary_id')->references('id')->on('customers')->onDelete('cascade');
            
            // Ensure one user can only use a referral code once
            $table->unique(['referral_id', 'beneficiary_id']);
            $table->index(['referral_id', 'used_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('referral_uses');
    }
};
