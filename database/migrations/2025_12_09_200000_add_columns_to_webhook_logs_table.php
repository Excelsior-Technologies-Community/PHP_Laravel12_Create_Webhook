<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('webhook_logs', function (Blueprint $table) {
            $table->string('source')->default('general')->after('payload');   // stripe, razorpay, whatsapp
            $table->string('event_type')->nullable()->after('source');        // order.created, payment.success
            $table->enum('status', ['pending', 'processed', 'failed'])->default('pending')->after('event_type');
            $table->integer('retry_count')->default(0)->after('status');
            $table->text('error_message')->nullable()->after('retry_count');
        });
    }

    public function down(): void
    {
        Schema::table('webhook_logs', function (Blueprint $table) {
            $table->dropColumn(['source', 'event_type', 'status', 'retry_count', 'error_message']);
        });
    }
};
