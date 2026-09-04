<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use App\Jobs\ProcessWebhookJob;
use Illuminate\Http\Request;

class WebhookLogController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | List Webhook Logs
    |--------------------------------------------------------------------------
    */

    public function index(Request $request)
    {
        $query = WebhookLog::latest();

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        if ($request->filled('source')) {
            $query->bySource($request->source);
        }

        if ($request->filled('event_type')) {
            $query->byEvent($request->event_type);
        }

        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        if ($request->filled('duplicate')) {

            $query->where(
                'is_duplicate',
                $request->duplicate === 'yes'
            );
        }

        return response()->json(
            $query->paginate(20)
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Replay Webhook
    |--------------------------------------------------------------------------
    */

    public function replay(int $id)
    {
        $log = WebhookLog::findOrFail($id);

        if ($log->is_duplicate) {

            return response()->json([
                'error' => 'Duplicate webhook cannot be replayed directly.',
            ], 422);
        }

        $log->update([
            'status' => 'pending',
            'retry_count' => 0,
            'error_message' => null,
            'processed_at' => null,
        ]);

        ProcessWebhookJob::dispatch($log);

        return response()->json([
            'status' => 'replayed',
            'id' => $log->id,
        ]);
    }
}