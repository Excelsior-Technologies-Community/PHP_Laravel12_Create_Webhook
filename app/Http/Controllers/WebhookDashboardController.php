<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WebhookLog;
use App\Jobs\ProcessWebhookJob;

class WebhookDashboardController extends Controller
{
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

        $logs = $query->paginate(15)->withQueryString();

        $stats = [
            'total'     => WebhookLog::count(),
            'pending'   => WebhookLog::byStatus('pending')->count(),
            'processed' => WebhookLog::byStatus('processed')->count(),
            'failed'    => WebhookLog::byStatus('failed')->count(),
        ];

        return view('webhook.dashboard', compact('logs', 'stats'));
    }

    public function show(int $id)
    {
        $log = WebhookLog::findOrFail($id);
        return view('webhook.show', compact('log'));
    }

    public function replay(int $id)
    {
        $log = WebhookLog::findOrFail($id);
        $log->update(['status' => 'pending', 'retry_count' => 0, 'error_message' => null]);
        ProcessWebhookJob::dispatch($log);

        return redirect()->route('webhook.dashboard')->with('success', "Webhook #{$id} replayed successfully!");
    }
}
