<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessWebhookJob;
use App\Models\WebhookLog;
use Illuminate\Http\Request;

class WebhookDashboardController extends Controller
{
    /**
     * Webhook Dashboard
     */
    public function index(Request $request)
    {
        $query = WebhookLog::query()->latest();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {

            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                $q->where('id', $search)
                    ->orWhere('webhook_id', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Status Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->byStatus($request->status);
        }

        /*
        |--------------------------------------------------------------------------
        | Source Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('source')) {
            $query->bySource($request->source);
        }

        /*
        |--------------------------------------------------------------------------
        | Event Type Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('event_type')) {

            $query->where(
                'event_type',
                'like',
                '%' . $request->event_type . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {
            $query->byDate($request->date);
        }

        /*
        |--------------------------------------------------------------------------
        | Duplicate Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('duplicate')) {

            if ($request->duplicate === 'yes') {

                $query->where('is_duplicate', true);
            } elseif ($request->duplicate === 'no') {

                $query->where('is_duplicate', false);
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->paginate(5)
            ->withQueryString();

        /*
        |--------------------------------------------------------------------------
        | Dashboard Statistics
        |--------------------------------------------------------------------------
        */

        $stats = [

            'total' => WebhookLog::where(
                'is_duplicate',
                false
            )->count(),

            'pending' => WebhookLog::where(
                'is_duplicate',
                false
            )
                ->where('status', 'pending')
                ->count(),

            'processed' => WebhookLog::where(
                'is_duplicate',
                false
            )
                ->where('status', 'processed')
                ->count(),

            'failed' => WebhookLog::where(
                'is_duplicate',
                false
            )
                ->where('status', 'failed')
                ->count(),

            'duplicates' => WebhookLog::where(
                'is_duplicate',
                true
            )->count(),
        ];

        return view(
            'webhook.dashboard',
            compact(
                'logs',
                'stats'
            )
        );
    }

    /**
     * Show Webhook Details
     */
    public function show(int $id)
    {
        $log = WebhookLog::findOrFail($id);

        return view(
            'webhook.show',
            compact('log')
        );
    }

    /**
     * Replay Webhook
     */
    public function replay(int $id)
    {
        $log = WebhookLog::findOrFail($id);

        /*
        |--------------------------------------------------------------------------
        | Prevent Duplicate Replay
        |--------------------------------------------------------------------------
        */

        if ($log->is_duplicate) {

            return redirect()
                ->route('webhook.dashboard')
                ->with(
                    'error',
                    "Duplicate webhook #{$id} cannot be replayed directly."
                );
        }

        /*
        |--------------------------------------------------------------------------
        | Reset Processing Information
        |--------------------------------------------------------------------------
        */

        $log->update([

            'status' => 'pending',

            'retry_count' => 0,

            'error_message' => null,

            'processed_at' => null,

        ]);

        /*
        |--------------------------------------------------------------------------
        | Dispatch Job
        |--------------------------------------------------------------------------
        */

        ProcessWebhookJob::dispatch($log);

        return redirect()
            ->route('webhook.dashboard')
            ->with(
                'success',
                "Webhook #{$id} replayed successfully!"
            );
    }
}
