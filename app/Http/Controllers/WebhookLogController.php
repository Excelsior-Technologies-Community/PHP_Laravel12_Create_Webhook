<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebhookLog;
use App\Jobs\ProcessWebhookJob;

class WebhookLogController extends Controller
{
    // GET /api/webhook-logs
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

        return response()->json($query->paginate(20));
    }

    // POST /api/webhook-logs/{id}/replay
    public function replay(int $id)
    {
        $log = WebhookLog::findOrFail($id);

        $log->update(['status' => 'pending', 'retry_count' => 0, 'error_message' => null]);

        ProcessWebhookJob::dispatch($log);

        return response()->json(['status' => 'replayed', 'id' => $log->id]);
    }
}
