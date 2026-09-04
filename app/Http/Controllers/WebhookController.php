<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Jobs\ProcessWebhookJob;
use App\Models\WebhookLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WebhookController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Allowed Events Per Source
    |--------------------------------------------------------------------------
    */

    protected array $allowedEvents = [
        'stripe' => [
            'payment.success',
            'payment.failed',
            'order.created',
        ],

        'razorpay' => [
            'payment.captured',
            'payment.failed',
        ],

        'whatsapp' => [
            'message.received',
            'message.delivered',
        ],

        'general' => [],
    ];

    /*
    |--------------------------------------------------------------------------
    | Receive Webhook
    |--------------------------------------------------------------------------
    */

    public function handle(Request $request, string $source = 'general')
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Source
        |--------------------------------------------------------------------------
        */

        $allowedSources = [
            'general',
            'stripe',
            'razorpay',
            'whatsapp',
        ];

        if (!in_array($source, $allowedSources, true)) {
            return response()->json([
                'error' => 'Unsupported webhook source.',
            ], 400);
        }

        /*
        |--------------------------------------------------------------------------
        | Get Secret
        |--------------------------------------------------------------------------
        */

        $secret = config("services.webhooks.{$source}.secret")
            ?? config('services.webhook_secret');

        if (!$secret) {
            Log::error("Webhook secret is not configured for source: {$source}");

            return response()->json([
                'error' => 'Webhook configuration error.',
            ], 500);
        }

        /*
        |--------------------------------------------------------------------------
        | Basic Secret Validation
        |--------------------------------------------------------------------------
        */

        $providedSecret = $request->header('X-Webhook-Secret');

        if (!$providedSecret || !hash_equals($secret, $providedSecret)) {
            Log::warning(
                "Webhook unauthorized for source: {$source}",
                [
                    'ip' => $request->ip(),
                ]
            );

            return response()->json([
                'error' => 'Unauthorized',
            ], 403);
        }

        /*
        |--------------------------------------------------------------------------
        | HMAC Signature Verification
        |--------------------------------------------------------------------------
        |
        | Client must send:
        |
        | X-Webhook-Signature: sha256=HASH
        |
        */

        $signature = $request->header('X-Webhook-Signature');

        if (!$signature) {
            Log::warning(
                "Webhook signature missing for source: {$source}"
            );

            return response()->json([
                'error' => 'Webhook signature is required.',
            ], 401);
        }

        $rawPayload = $request->getContent();

        $expectedSignature = hash_hmac(
            'sha256',
            $rawPayload,
            $secret
        );

        $providedSignature = str_starts_with($signature, 'sha256=')
            ? substr($signature, 7)
            : $signature;

        if (!hash_equals($expectedSignature, $providedSignature)) {
            Log::warning(
                "Invalid webhook signature for source: {$source}",
                [
                    'ip' => $request->ip(),
                ]
            );

            return response()->json([
                'error' => 'Invalid webhook signature.',
            ], 401);
        }

        /*
        |--------------------------------------------------------------------------
        | Validate JSON Payload
        |--------------------------------------------------------------------------
        */

        $payload = $request->all();

        if (empty($payload)) {
            return response()->json([
                'error' => 'Webhook payload cannot be empty.',
            ], 422);
        }

        /*
        |--------------------------------------------------------------------------
        | Event Type
        |--------------------------------------------------------------------------
        */

        $eventType = $payload['event']
            ?? $payload['type']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Event Filtering
        |--------------------------------------------------------------------------
        */

        $allowed = $this->allowedEvents[$source] ?? [];

        if (
            !empty($allowed) &&
            !in_array($eventType, $allowed, true)
        ) {
            Log::info(
                "Webhook event '{$eventType}' ignored for source: {$source}"
            );

            return response()->json([
                'status' => 'ignored',
                'reason' => 'event not allowed',
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Extract Webhook ID
        |--------------------------------------------------------------------------
        |
        | Supports:
        | X-Webhook-ID header
        | payload.id
        | payload.webhook_id
        */

        $webhookId = $request->header('X-Webhook-ID')
            ?? $payload['webhook_id']
            ?? $payload['id']
            ?? null;

        /*
        |--------------------------------------------------------------------------
        | Duplicate Detection
        |--------------------------------------------------------------------------
        */

        if ($webhookId) {
            $existingWebhook = WebhookLog::where('source', $source)
                ->where('webhook_id', $webhookId)
                ->where('is_duplicate', false)
                ->first();

            if ($existingWebhook) {

                $duplicateLog = WebhookLog::create([
                    'payload' => $payload,
                    'source' => $source,
                    'event_type' => $eventType,
                    'webhook_id' => $webhookId,
                    'status' => 'processed',
                    'retry_count' => 0,
                    'error_message' => 'Duplicate webhook delivery.',
                    'is_duplicate' => true,
                    'duplicate_of' => $existingWebhook->id,
                    'processed_at' => now(),
                ]);

                Log::warning(
                    "Duplicate webhook detected",
                    [
                        'source' => $source,
                        'webhook_id' => $webhookId,
                        'original_id' => $existingWebhook->id,
                        'duplicate_id' => $duplicateLog->id,
                    ]
                );

                return response()->json([
                    'status' => 'duplicate',
                    'message' => 'Webhook has already been received.',
                    'id' => $duplicateLog->id,
                    'original_id' => $existingWebhook->id,
                ], 200);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Store Webhook
        |--------------------------------------------------------------------------
        */

        Log::info(
            "Webhook received [{$source}]",
            [
                'webhook_id' => $webhookId,
                'event' => $eventType,
            ]
        );

        $log = WebhookLog::create([
            'payload' => $payload,
            'source' => $source,
            'event_type' => $eventType,
            'webhook_id' => $webhookId,
            'status' => 'pending',
            'retry_count' => 0,
            'is_duplicate' => false,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Queue Processing
        |--------------------------------------------------------------------------
        */

        ProcessWebhookJob::dispatch($log);

        return response()->json([
            'status' => 'queued',
            'id' => $log->id,
            'webhook_id' => $webhookId,
        ], 202);
    }
}