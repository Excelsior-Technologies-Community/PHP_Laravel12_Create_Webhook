<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WebhookLog;
use App\Jobs\ProcessWebhookJob;

class WebhookController extends Controller
{
    // Allowed events per source (empty array = allow all)
    protected array $allowedEvents = [
        'stripe'    => ['payment.success', 'payment.failed', 'order.created'],
        'razorpay'  => ['payment.captured', 'payment.failed'],
        'whatsapp'  => ['message.received', 'message.delivered'],
        'general'   => [],
    ];

    public function handle(Request $request, string $source = 'general')
    {
        // Validate secret key for the given source
        $secret = config("services.webhooks.{$source}.secret")
                  ?? config('services.webhook_secret');

        if ($request->header('X-Webhook-Secret') !== $secret) {
            Log::warning("Webhook unauthorized for source: {$source}");
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $payload   = $request->all();
        $eventType = $payload['event'] ?? null;

        // Event filtering — reject events not in allowed list
        $allowed = $this->allowedEvents[$source] ?? [];
        if (!empty($allowed) && !in_array($eventType, $allowed)) {
            Log::info("Webhook event '{$eventType}' ignored for source: {$source}");
            return response()->json(['status' => 'ignored', 'reason' => 'event not allowed']);
        }

        Log::info("Webhook received [{$source}]:", $payload);

        // Store with status = pending
        $log = WebhookLog::create([
            'payload'    => $payload,
            'source'     => $source,
            'event_type' => $eventType,
            'status'     => 'pending',
        ]);

        // Dispatch to queue for background processing
        ProcessWebhookJob::dispatch($log);

        return response()->json(['status' => 'queued', 'id' => $log->id]);
    }
}
