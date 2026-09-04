<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webhook_logs', function (Blueprint $table) {
            // Unique ID supplied by webhook provider
            $table->string('webhook_id')->nullable()->after('event_type');

            // Marks repeated webhook deliveries
            $table->boolean('is_duplicate')->default(false)->after('webhook_id');

            // Points to the original webhook when this is a duplicate
            $table->unsignedBigInteger('duplicate_of')->nullable()->after('is_duplicate');

            // Store when webhook processing started/completed
            $table->timestamp('processed_at')->nullable()->after('error_message');

            $table->index('webhook_id');
            $table->index('is_duplicate');
            $table->index('source');
            $table->index('event_type');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::table('webhook_logs', function (Blueprint $table) {
            $table->dropIndex(['webhook_id']);
            $table->dropIndex(['is_duplicate']);
            $table->dropIndex(['source']);
            $table->dropIndex(['event_type']);
            $table->dropIndex(['status']);

            $table->dropColumn([
                'webhook_id',
                'is_duplicate',
                'duplicate_of',
                'processed_at',
            ]);
        });
    }
};