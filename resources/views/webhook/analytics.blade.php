<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Webhook Analytics</title>

    <style>

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Segoe UI', sans-serif;
            background: #f0f2f5;
            color: #333;
        }

        .navbar {
            background: #1a1a2e;
            color: white;
            padding: 16px 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar h1 {
            font-size: 20px;
        }

        .navbar a {
            color: #aab4ff;
            text-decoration: none;
            font-size: 14px;
        }

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .page-title {
            margin-bottom: 25px;
        }

        .page-title h2 {
            font-size: 25px;
            color: #1a1a2e;
        }

        .page-title p {
            color: #777;
            margin-top: 5px;
        }

        /*
        |--------------------------------------------------------------------------
        | Stats
        |--------------------------------------------------------------------------
        */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }

        .stat-card .number {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-card .label {
            color: #888;
            font-size: 13px;
            text-transform: uppercase;
        }

        .blue {
            border-left: 4px solid #4361ee;
        }

        .green {
            border-left: 4px solid #2ec4b6;
        }

        .orange {
            border-left: 4px solid #f4a261;
        }

        .red {
            border-left: 4px solid #e63946;
        }

        /*
        |--------------------------------------------------------------------------
        | Panels
        |--------------------------------------------------------------------------
        */

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .panel {
            background: white;
            border-radius: 10px;
            padding: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,.07);
        }

        .panel h3 {
            margin-bottom: 20px;
            color: #1a1a2e;
        }

        /*
        |--------------------------------------------------------------------------
        | Progress
        |--------------------------------------------------------------------------
        */

        .progress-row {
            margin-bottom: 18px;
        }

        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 7px;
            font-size: 14px;
        }

        .progress-bar {
            height: 10px;
            background: #eee;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #4361ee;
            border-radius: 10px;
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        .status-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #eee;
        }

        .status-item:last-child {
            border-bottom: none;
        }

        /*
        |--------------------------------------------------------------------------
        | Recent Activity
        |--------------------------------------------------------------------------
        */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #1a1a2e;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 13px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .processed {
            background: #d1f2eb;
            color: #0e6655;
        }

        .pending {
            background: #fff3cd;
            color: #856404;
        }

        .failed {
            background: #fde8e8;
            color: #c0392b;
        }

        .duplicate {
            background: #e8eaf6;
            color: #3949ab;
        }

        .source {
            background: #e8eaf6;
            color: #3949ab;
        }

        .quick-links {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 9px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .btn-primary {
            background: #4361ee;
            color: white;
        }

        .btn-secondary {
            background: #eee;
            color: #555;
        }

        @media(max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid {
                grid-template-columns: 1fr;
            }
        }

        @media(max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 15px;
            }

            .container {
                padding: 0 12px;
            }
        }

    </style>

</head>

<body>

<div class="navbar">

    <h1>📊 Webhook Analytics</h1>

    <a href="{{ route('webhook.dashboard') }}">
        ← Dashboard
    </a>

</div>

<div class="container">

    <div class="page-title">

        <h2>Webhook Analytics & Monitoring</h2>

        <p>
            Monitor webhook traffic, processing performance and duplicate deliveries.
        </p>

    </div>

    <div class="quick-links">

        <a
            href="{{ route('webhook.dashboard') }}"
            class="btn btn-secondary"
        >
            📋 Webhook Logs
        </a>

        <a
            href="{{ route('webhook.analytics') }}"
            class="btn btn-primary"
        >
            📊 Analytics
        </a>

    </div>

    <!-- Main Stats -->

    <div class="stats">

        <div class="stat-card blue">

            <div class="number">
                {{ $total }}
            </div>

            <div class="label">
                Total Webhooks
            </div>

        </div>

        <div class="stat-card green">

            <div class="number">
                {{ $successRate }}%
            </div>

            <div class="label">
                Success Rate
            </div>

        </div>

        <div class="stat-card red">

            <div class="number">
                {{ $failed }}
            </div>

            <div class="label">
                Failed
            </div>

        </div>

        <div class="stat-card orange">

            <div class="number">
                {{ $duplicates }}
            </div>

            <div class="label">
                Duplicates
            </div>

        </div>

    </div>

    <!-- Secondary Stats -->

    <div class="stats">

        <div class="stat-card blue">

            <div class="number">
                {{ $today }}
            </div>

            <div class="label">
                Today
            </div>

        </div>

        <div class="stat-card green">

            <div class="number">
                {{ $thisWeek }}
            </div>

            <div class="label">
                This Week
            </div>

        </div>

        <div class="stat-card orange">

            <div class="number">
                {{ $pending }}
            </div>

            <div class="label">
                Pending
            </div>

        </div>

        <div class="stat-card red">

            <div class="number">
                {{ $failureRate }}%
            </div>

            <div class="label">
                Failure Rate
            </div>

        </div>

    </div>

    <div class="grid">

        <!-- Source Statistics -->

        <div class="panel">

            <h3>🌐 Webhooks By Source</h3>

            @php
                $maxSource = $sourceStats->max('total') ?: 1;
            @endphp

            @forelse($sourceStats as $source)

                <div class="progress-row">

                    <div class="progress-header">

                        <strong>
                            {{ ucfirst($source->source) }}
                        </strong>

                        <span>
                            {{ $source->total }}
                        </span>

                    </div>

                    <div class="progress-bar">

                        <div
                            class="progress-fill"
                            style="width: {{ ($source->total / $maxSource) * 100 }}%"
                        ></div>

                    </div>

                </div>

            @empty

                <p>No source data available.</p>

            @endforelse

        </div>

        <!-- Event Statistics -->

        <div class="panel">

            <h3>⚡ Top Events</h3>

            @php
                $maxEvent = $eventStats->max('total') ?: 1;
            @endphp

            @forelse($eventStats as $event)

                <div class="progress-row">

                    <div class="progress-header">

                        <strong>
                            {{ $event->event_type }}
                        </strong>

                        <span>
                            {{ $event->total }}
                        </span>

                    </div>

                    <div class="progress-bar">

                        <div
                            class="progress-fill"
                            style="width: {{ ($event->total / $maxEvent) * 100 }}%"
                        ></div>

                    </div>

                </div>

            @empty

                <p>No event data available.</p>

            @endforelse

        </div>

    </div>

    <!-- Status -->

    <div class="panel" style="margin-bottom:20px;">

        <h3>📌 Processing Status</h3>

        <div class="status-item">

            <span>✅ Processed</span>

            <strong>
                {{ $statusStats['processed'] }}
            </strong>

        </div>

        <div class="status-item">

            <span>⏳ Pending</span>

            <strong>
                {{ $statusStats['pending'] }}
            </strong>

        </div>

        <div class="status-item">

            <span>❌ Failed</span>

            <strong>
                {{ $statusStats['failed'] }}
            </strong>

        </div>

        <div class="status-item">

            <span>🔁 Duplicates</span>

            <strong>
                {{ $statusStats['duplicates'] }}
            </strong>

        </div>

    </div>

    <!-- Recent Activity -->

    <div class="panel">

        <h3>🕐 Recent Webhook Activity</h3>

        <div class="table-wrapper">

            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Source</th>
                        <th>Event</th>
                        <th>Status</th>
                        <th>Webhook ID</th>
                        <th>Received</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($recentWebhooks as $webhook)

                    <tr>

                        <td>
                            #{{ $webhook->id }}
                        </td>

                        <td>

                            <span class="badge source">
                                {{ $webhook->source }}
                            </span>

                        </td>

                        <td>
                            {{ $webhook->event_type ?? '—' }}
                        </td>

                        <td>

                            @if($webhook->is_duplicate)

                                <span class="badge duplicate">
                                    Duplicate
                                </span>

                            @else

                                <span class="badge {{ $webhook->status }}">
                                    {{ ucfirst($webhook->status) }}
                                </span>

                            @endif

                        </td>

                        <td>
                            {{ $webhook->webhook_id ?? '—' }}
                        </td>

                        <td>
                            {{ $webhook->created_at->format('d M Y H:i') }}
                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">
                            No webhook activity found.
                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

</body>

</html>