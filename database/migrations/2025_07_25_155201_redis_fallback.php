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
        Schema::create('redis_fallback', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('identifier');  // e.g. dm_id
            $table->string('field');       // e.g. has_order or order_customer_ids
            $table->text('value')->nullable();
            $table->timestamps();

            $table->index('identifier');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
