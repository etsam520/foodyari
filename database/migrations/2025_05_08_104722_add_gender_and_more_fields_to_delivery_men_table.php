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
        Schema::table('delivery_men', function (Blueprint $table) {
            $table->after('fuel_rate', function ($table) {
                $table->string('gender')->nullable();
                $table->string('dob')->nullable();
                $table->string('marital_status')->nullable();
                $table->string('anniversary_date')->nullable();
                $table->string('blood_group')->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('delivery_men', function (Blueprint $table) {
            //
        });
    }
};
