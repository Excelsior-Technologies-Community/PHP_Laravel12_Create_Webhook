<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Support\Facades\DB;

class WebhookAnalyticsController extends Controller
{
    /**
     * Webhook Analytics Dashboard
     */
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $total = WebhookLog::count();

        $processed = WebhookLog::where(
            'status',
            'processed'
        )
            ->where('is_duplicate', false)
            ->count();

        $pending = WebhookLog::where(
            'status',
            'pending'
        )
            ->where('is_duplicate', false)
            ->count();

        $failed = WebhookLog::where(
            'status',
            'failed'
        )
            ->where('is_duplicate', false)
            ->count();

        $duplicates = WebhookLog::where(
            'is_duplicate',
            true
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Original Webhooks
        |--------------------------------------------------------------------------
        */

        $originalTotal = WebhookLog::where(
            'is_duplicate',
            false
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Success Rate
        |--------------------------------------------------------------------------
        */

        $successRate = $originalTotal > 0
            ? round(
                ($processed / $originalTotal) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Failure Rate
        |--------------------------------------------------------------------------
        */

        $failureRate = $originalTotal > 0
            ? round(
                ($failed / $originalTotal) * 100,
                2
            )
            : 0;

        /*
        |--------------------------------------------------------------------------
        | Today
        |--------------------------------------------------------------------------
        */

        $today = WebhookLog::whereDate(
            'created_at',
            today()
        )->count();

        /*
        |--------------------------------------------------------------------------
        | This Week
        |--------------------------------------------------------------------------
        */

        $thisWeek = WebhookLog::whereBetween(
            'created_at',
            [
                now()->startOfWeek(),
                now()->endOfWeek(),
            ]
        )->count();

        /*
        |--------------------------------------------------------------------------
        | This Month
        |--------------------------------------------------------------------------
        */

        $thisMonth = WebhookLog::whereBetween(
            'created_at',
            [
                now()->startOfMonth(),
                now()->endOfMonth(),
            ]
        )->count();

        /*
        |--------------------------------------------------------------------------
        | Source Statistics
        |--------------------------------------------------------------------------
        */

        $sourceStats = WebhookLog::select(
            'source',
            DB::raw('COUNT(*) as total')
        )
            ->where(
                'is_duplicate',
                false
            )
            ->groupBy('source')
            ->orderByDesc('total')
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Event Statistics
        |--------------------------------------------------------------------------
        */

        $eventStats = WebhookLog::select(
            'event_type',
            DB::raw('COUNT(*) as total')
        )
            ->where(
                'is_duplicate',
                false
            )
            ->whereNotNull('event_type')
            ->groupBy('event_type')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Status Statistics
        |--------------------------------------------------------------------------
        */

        $statusStats = [

            'processed' => $processed,

            'pending' => $pending,

            'failed' => $failed,

            'duplicates' => $duplicates,

        ];

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        $recentWebhooks = WebhookLog::latest()
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Average Retry Count
        |--------------------------------------------------------------------------
        */

        $averageRetries = WebhookLog::where(
            'is_duplicate',
            false
        )->avg('retry_count');

        $averageRetries = round(
            $averageRetries ?? 0,
            2
        );

        /*
        |--------------------------------------------------------------------------
        | Return View
        |--------------------------------------------------------------------------
        */

        return view(
            'webhook.analytics',
            compact(
                'total',
                'processed',
                'pending',
                'failed',
                'duplicates',
                'originalTotal',
                'successRate',
                'failureRate',
                'today',
                'thisWeek',
                'thisMonth',
                'sourceStats',
                'eventStats',
                'statusStats',
                'recentWebhooks',
                'averageRetries'
            )
        );
    }
}
