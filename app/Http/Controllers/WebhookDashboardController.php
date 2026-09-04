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

        // Status filter
        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        // Source filter
        if ($request->filled('source')) {
            $query->bySource($request->source);
        }

        // Event type filter
        if ($request->filled('event_type')) {
            $query->byEvent($request->event_type);
        }

        // Date filter
        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        // Duplicate filter
        if ($request->filled('duplicate')) {

            if ($request->duplicate === 'yes') {
                $query->where('is_duplicate', true);
            }

            if ($request->duplicate === 'no') {
                $query->where('is_duplicate', false);
            }
        }

        $logs = $query->paginate(15)->withQueryString();

        $stats = [
            'total' => WebhookLog::where('is_duplicate', false)->count(),

            'pending' => WebhookLog::where('is_duplicate', false)
                ->where('status', 'pending')
                ->count(),

            'processed' => WebhookLog::where('is_duplicate', false)
                ->where('status', 'processed')
                ->count(),

            'failed' => WebhookLog::where('is_duplicate', false)
                ->where('status', 'failed')
                ->count(),

            'duplicates' => WebhookLog::where('is_duplicate', true)
                ->count(),
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

        if ($log->is_duplicate) {
            return redirect()
                ->route('webhook.dashboard')
                ->with('error', "Duplicate webhook #{$id} cannot be replayed directly.");
        }

        $log->update([
            'status' => 'pending',
            'retry_count' => 0,
            'error_message' => null,
            'processed_at' => null,
        ]);

        ProcessWebhookJob::dispatch($log);

        return redirect()
            ->route('webhook.dashboard')
            ->with(
                'success',
                "Webhook #{$id} replayed successfully!"
            );
    }
}
