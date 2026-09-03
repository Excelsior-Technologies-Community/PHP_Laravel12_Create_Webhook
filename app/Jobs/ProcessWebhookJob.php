<?php

namespace App\Jobs;

use App\Models\WebhookLog;
use Illuminate\Support\Facades\Log;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessWebhookJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 3;
    public int $backoff = 60;

    public function __construct(public WebhookLog $webhookLog) {}

    public function handle(): void
    {
        try {
            Log::info('Processing webhook', [
                'id'     => $this->webhookLog->id,
                'source' => $this->webhookLog->source,
                'event'  => $this->webhookLog->event_type,
            ]);

            // Add your business logic per source/event here
            // e.g. if ($this->webhookLog->source === 'stripe') { ... }

            $this->webhookLog->update(['status' => 'processed']);

        } catch (\Throwable $e) {
            $this->webhookLog->increment('retry_count');
            $this->webhookLog->update([
                'status'        => 'failed',
                'error_message' => $e->getMessage(),
            ]);
            throw $e;
        }
    }

    public function failed(\Throwable $e): void
    {
        $this->webhookLog->update([
            'status'        => 'failed',
            'error_message' => $e->getMessage(),
        ]);
    }
}
