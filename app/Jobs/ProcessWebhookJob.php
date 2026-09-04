<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;

    public int $backoff = 60;

    public function __construct(
        public WebhookLog $webhookLog
    ) {
    }

    public function handle(): void
    {
        try {

            Log::info('Processing webhook', [
                'id' => $this->webhookLog->id,
                'source' => $this->webhookLog->source,
                'event' => $this->webhookLog->event_type,
                'webhook_id' => $this->webhookLog->webhook_id,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Business Logic
            |--------------------------------------------------------------------------
            */

            // Add source/event specific business logic here.

            /*
            |--------------------------------------------------------------------------
            | Mark Processed
            |--------------------------------------------------------------------------
            */

            $this->webhookLog->update([
                'status' => 'processed',
                'processed_at' => now(),
                'error_message' => null,
            ]);

        } catch (\Throwable $e) {

            $this->webhookLog->increment('retry_count');

            $this->webhookLog->update([
                'status' => 'failed',
                'error_message' => $e->getMessage(),
            ]);

            Log::error('Webhook processing failed', [
                'id' => $this->webhookLog->id,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        $this->webhookLog->update([
            'status' => 'failed',
            'error_message' => $e->getMessage(),
        ]);

        Log::error('Webhook permanently failed', [
            'id' => $this->webhookLog->id,
            'error' => $e->getMessage(),
        ]);
    }
}