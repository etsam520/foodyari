<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('referrals', function (Blueprint $table) {
            $table->id();
            $table->string('referral_code', 20)->unique(); // Unique referral code
            $table->unsignedBigInteger('sponsor_id'); // Customer who refers
            $table->unsignedBigInteger('beneficiary_id')->nullable(); // Customer who uses the code
            $table->timestamp('used_at')->nullable(); // When the referral was used
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('sponsor_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('beneficiary_id')->references('id')->on('customers')->onDelete('cascade');
            $table->index(['referral_code', 'is_active']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('referrals');
    }
};
