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
        Schema::table('messages', function (Blueprint $table) {
            $table->json('attachments')->nullable()->after('attachment'); // For multiple files
            $table->string('attachment_type')->nullable()->after('attachments'); // image, document, etc.
            $table->boolean('is_deleted')->default(false)->after('is_seen'); // For soft delete
            $table->timestamp('deleted_at')->nullable()->after('is_deleted');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn(['attachments', 'attachment_type', 'is_deleted', 'deleted_at']);
        });
    }
};
