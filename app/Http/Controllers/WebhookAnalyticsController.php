<?php

namespace App\Http\Controllers;

use App\Models\WebhookLog;
use Illuminate\Support\Facades\DB;

class WebhookAnalyticsController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | Basic Statistics
        |--------------------------------------------------------------------------
        */

        $total = WebhookLog::count();

        $processed = WebhookLog::where('status', 'processed')
            ->where('is_duplicate', false)
            ->count();

        $pending = WebhookLog::where('status', 'pending')
            ->where('is_duplicate', false)
            ->count();

        $failed = WebhookLog::where('status', 'failed')
            ->where('is_duplicate', false)
            ->count();

        $duplicates = WebhookLog::where('is_duplicate', true)
            ->count();

        $originalTotal = WebhookLog::where('is_duplicate', false)
            ->count();

        /*
        |--------------------------------------------------------------------------
        | Success Rate
        |--------------------------------------------------------------------------
        */

        $successRate = $originalTotal > 0
            ? round(($processed / $originalTotal) * 100, 2)
            : 0;

        $failureRate = $originalTotal > 0
            ? round(($failed / $originalTotal) * 100, 2)
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
        | Source Statistics
        |--------------------------------------------------------------------------
        */

        $sourceStats = WebhookLog::select(
                'source',
                DB::raw('COUNT(*) as total')
            )
            ->where('is_duplicate', false)
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
            ->where('is_duplicate', false)
            ->whereNotNull('event_type')
            ->groupBy('event_type')
            ->orderByDesc('total')
            ->limit(10)
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
            ->limit(10)
            ->get();

        return view(
            'webhook.analytics',
            compact(
                'total',
                'processed',
                'pending',
                'failed',
                'duplicates',
                'successRate',
                'failureRate',
                'today',
                'thisWeek',
                'sourceStats',
                'eventStats',
                'statusStats',
                'recentWebhooks'
            )
        );
    }
}