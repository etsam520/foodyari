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
        Schema::table('deliveryman_attendances', function (Blueprint $table) {
            $table->timestamp('check_in')->nullable()->after('deliveryman_id');
            $table->timestamp('check_out')->nullable()->after('check_in');
            $table->text('check_in_location')->nullable()->after('check_out');
            $table->text('check_out_location')->nullable()->after('check_in_location');
            $table->string('check_in_image')->nullable()->after('check_out_location');
            $table->string('check_out_image')->nullable()->after('check_in_image');
            $table->string('check_in_meter')->nullable()->after('check_out_image');
            $table->string('check_out_meter')->nullable()->after('check_in_meter');
            $table->string('check_in_address')->nullable()->after('check_out_meter');
            $table->string('check_out_address')->nullable()->after('check_in_address');
            $table->string('check_in_note')->nullable()->after('check_out_address');
            $table->string('check_out_note')->nullable()->after('check_in_note');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('deliveryman_attendances', function (Blueprint $table) {
            $table->dropColumn([
                'check_in', 'check_out',
                'check_in_location', 'check_out_location',
                'check_in_image', 'check_out_image',
                'check_in_address', 'check_out_address',
                'check_in_note', 'check_out_note',
            ]);
        });
    }
};
