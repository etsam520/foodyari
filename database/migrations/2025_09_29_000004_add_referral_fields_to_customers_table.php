<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('referral_code', 20)->unique()->nullable()->after('remember_token');
            $table->unsignedBigInteger('referred_by')->nullable()->after('referral_code');
            $table->integer('successful_orders')->default(0)->after('referred_by');
            
            $table->foreign('referred_by')->references('id')->on('customers')->onDelete('set null');
            $table->index('referral_code');
        });
    }

    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropForeign(['referred_by']);
            $table->dropIndex(['referral_code']);
            $table->dropColumn(['referral_code', 'referred_by', 'successful_orders']);
        });
    }
};
