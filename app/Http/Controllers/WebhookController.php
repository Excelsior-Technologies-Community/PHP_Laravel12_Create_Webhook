<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Models\WebhookLog;

class WebhookController extends Controller
{
    /**
     * Handle incoming webhook requests.
     * This method validates the secret key, logs the payload,
     * and stores it inside the database.
     */
    public function handle(Request $request)
    {
        // Load secret key from configuration
        $secret = config('services.webhook_secret');

        /*
        |--------------------------------------------------------------------------
        | Security Check
        |--------------------------------------------------------------------------
        | The external service must send the secret key in this header:
        |    X-Webhook-Secret: <your_secret_key>
        | If the header value does not match, the request will be rejected.
        */
        if ($request->header('X-Webhook-Secret') !== $secret) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Log webhook payload to laravel.log for debugging
        Log::info('Webhook received:', $request->all());

        /*
        |--------------------------------------------------------------------------
        | Save webhook payload to database
        |--------------------------------------------------------------------------
        | Storing the payload allows viewing webhook history in admin panel,
        | reprocessing failed webhook attempts, and debugging.
        */
        WebhookLog::create([
            'payload' => $request->all(),
        ]);

        // Return success response to the webhook sender
        return response()->json(['status' => 'success']);
    }
}
