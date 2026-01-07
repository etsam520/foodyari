<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Remove the single beneficiary relationship
            $table->dropForeign(['beneficiary_id']);
            $table->dropColumn(['beneficiary_id', 'used_at']);
            
            // Add usage tracking fields
            $table->integer('total_uses')->default(0)->after('sponsor_id');
            $table->timestamp('last_used_at')->nullable()->after('total_uses');
        });
    }

    public function down()
    {
        Schema::table('referrals', function (Blueprint $table) {
            // Restore original structure
            $table->dropColumn(['total_uses', 'last_used_at']);
            $table->unsignedBigInteger('beneficiary_id')->nullable()->after('sponsor_id');
            $table->timestamp('used_at')->nullable()->after('beneficiary_id');
            
            $table->foreign('beneficiary_id')->references('id')->on('customers')->onDelete('cascade');
        });
    }
};
