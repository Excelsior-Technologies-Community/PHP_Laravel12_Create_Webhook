<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Webhook Analytics</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            background: #f4f6f9;
            color: #333;
        }

        /* Navbar */

        .navbar {
            background: #17172b;
            color: white;
            padding: 18px 35px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 21px;
        }

        .navbar a {
            color: #aab4ff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .container {
            max-width: 1250px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Header */

        .page-header {
            margin-bottom: 25px;
        }

        .page-header h2 {
            font-size: 27px;
            color: #17172b;
            margin-bottom: 7px;
        }

        .page-header p {
            color: #777;
        }

        /* Quick Links */

        .quick-links {
            display: flex;
            gap: 10px;
            margin-bottom: 25px;
        }

        .btn {
            display: inline-block;
            padding: 10px 17px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-primary {
            background: #4361ee;
            color: white;
        }

        .btn-secondary {
            background: #e9ecef;
            color: #555;
        }

        /* Stats */

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 16px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
            border-left: 5px solid #4361ee;
        }

        .stat-card.green {
            border-color: #2ec4b6;
        }

        .stat-card.red {
            border-color: #e63946;
        }

        .stat-card.orange {
            border-color: #f4a261;
        }

        .stat-number {
            font-size: 30px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
        }

        /* Panels */

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .panel {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        .panel h3 {
            color: #17172b;
            margin-bottom: 20px;
            font-size: 17px;
        }

        /* Progress */

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
            border-radius: 20px;
            overflow: hidden;
        }

        .progress-fill {
            height: 100%;
            background: #4361ee;
            border-radius: 20px;
        }

        /* Status */

        .status-item {
            display: flex;
            justify-content: space-between;
            padding: 13px 0;
            border-bottom: 1px solid #eee;
        }

        .status-item:last-child {
            border-bottom: none;
        }

        .status-item strong {
            font-size: 16px;
        }

        /* Table */

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th {
            background: #17172b;
            color: white;
            padding: 12px;
            text-align: left;
            font-size: 12px;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 13px;
        }

        /* Badge */

        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
            display: inline-block;
        }

        .badge-processed {
            background: #d1f2eb;
            color: #0e6655;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-failed {
            background: #fde8e8;
            color: #c0392b;
        }

        .badge-duplicate {
            background: #eee2ff;
            color: #7b2cbf;
        }

        .badge-source {
            background: #e8eaf6;
            color: #3949ab;
        }

        /* Retry */

        .retry-box {
            margin-top: 20px;
            padding: 15px;
            background: #f7f8ff;
            border-radius: 8px;
            border-left: 4px solid #4361ee;
        }

        .retry-box strong {
            font-size: 20px;
        }

        /* Responsive */

        @media(max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .grid {
                grid-template-columns: 1fr;
            }

        }

        @media(max-width: 550px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 15px;
            }

            .navbar h1 {
                font-size: 17px;
            }

            .container {
                padding: 0 12px;
            }

            .quick-links {
                flex-direction: column;
            }

        }
    </style>

</head>

<body>


    <!-- Navbar -->

    <div class="navbar">

        <h1>
            📊 Webhook Analytics
        </h1>

        <a href="{{ route('webhook.dashboard') }}">
            ← Dashboard
        </a>

    </div>


    <div class="container">


        <!-- Header -->

        <div class="page-header">

            <h2>
                Webhook Analytics & Monitoring
            </h2>

            <p>
                Monitor webhook traffic, processing performance,
                failures and duplicate deliveries.
            </p>

        </div>


        <!-- Links -->

        <div class="quick-links">

            <a
                href="{{ route('webhook.dashboard') }}"
                class="btn btn-secondary">
                📋 Webhook Logs
            </a>

            <a
                href="{{ route('webhook.analytics') }}"
                class="btn btn-primary">
                📊 Analytics
            </a>

        </div>


        <!-- Main Statistics -->

        <div class="stats">


            <div class="stat-card">

                <div class="stat-number">
                    {{ $total }}
                </div>

                <div class="stat-label">
                    Total Webhooks
                </div>

            </div>


            <div class="stat-card green">

                <div class="stat-number">
                    {{ $successRate }}%
                </div>

                <div class="stat-label">
                    Success Rate
                </div>

            </div>


            <div class="stat-card red">

                <div class="stat-number">
                    {{ $failed }}
                </div>

                <div class="stat-label">
                    Failed
                </div>

            </div>


            <div class="stat-card orange">

                <div class="stat-number">
                    {{ $duplicates }}
                </div>

                <div class="stat-label">
                    Duplicates
                </div>

            </div>

        </div>


        <!-- Secondary Statistics -->

        <div class="stats">


            <div class="stat-card">

                <div class="stat-number">
                    {{ $today }}
                </div>

                <div class="stat-label">
                    Today
                </div>

            </div>


            <div class="stat-card green">

                <div class="stat-number">
                    {{ $thisWeek }}
                </div>

                <div class="stat-label">
                    This Week
                </div>

            </div>


            <div class="stat-card orange">

                <div class="stat-number">
                    {{ $thisMonth }}
                </div>

                <div class="stat-label">
                    This Month
                </div>

            </div>


            <div class="stat-card red">

                <div class="stat-number">
                    {{ $failureRate }}%
                </div>

                <div class="stat-label">
                    Failure Rate
                </div>

            </div>

        </div>


        <!-- Source + Events -->

        <div class="grid">


            <!-- Sources -->

            <div class="panel">

                <h3>
                    🌐 Webhooks By Source
                </h3>

                @php

                $maxSource =
                $sourceStats->max('total') ?: 1;

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
                            style="
                                    width:
                                    {{ ($source->total / $maxSource) * 100 }}%
                                "></div>

                    </div>

                </div>

                @empty

                <p>
                    No source data available.
                </p>

                @endforelse

            </div>


            <!-- Events -->

            <div class="panel">

                <h3>
                    ⚡ Top Events
                </h3>

                @php

                $maxEvent =
                $eventStats->max('total') ?: 1;

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
                            style="
                                    width:
                                    {{ ($event->total / $maxEvent) * 100 }}%
                                "></div>

                    </div>

                </div>

                @empty

                <p>
                    No event data available.
                </p>

                @endforelse

            </div>

        </div>


        <!-- Processing Status -->

        <div
            class="panel"
            style="margin-bottom:20px;">

            <h3>
                📌 Processing Status
            </h3>


            <div class="status-item">

                <span>
                    ✅ Processed
                </span>

                <strong>
                    {{ $statusStats['processed'] }}
                </strong>

            </div>


            <div class="status-item">

                <span>
                    ⏳ Pending
                </span>

                <strong>
                    {{ $statusStats['pending'] }}
                </strong>

            </div>


            <div class="status-item">

                <span>
                    ❌ Failed
                </span>

                <strong>
                    {{ $statusStats['failed'] }}
                </strong>

            </div>


            <div class="status-item">

                <span>
                    🔁 Duplicates
                </span>

                <strong>
                    {{ $statusStats['duplicates'] }}
                </strong>

            </div>


            <div class="retry-box">

                <div>
                    Average Retry Count
                </div>

                <strong>
                    {{ $averageRetries }}
                </strong>

            </div>

        </div>


        <!-- Recent Activity -->

        <div class="panel">

            <h3>
                🕐 Recent Webhook Activity
            </h3>


            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                ID
                            </th>

                            <th>
                                Source
                            </th>

                            <th>
                                Event
                            </th>

                            <th>
                                Status
                            </th>

                            <th>
                                Webhook ID
                            </th>

                            <th>
                                Received
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($recentWebhooks as $webhook)

                        <tr>

                            <td>
                                #{{ $webhook->id }}
                            </td>


                            <td>

                                <span class="badge badge-source">
                                    {{ ucfirst($webhook->source) }}
                                </span>

                            </td>


                            <td>
                                {{ $webhook->event_type ?? '—' }}
                            </td>


                            <td>

                                @if($webhook->is_duplicate)

                                <span class="badge badge-duplicate">
                                    🔁 Duplicate
                                </span>

                                @else

                                <span
                                    class="badge badge-{{ $webhook->status }}">
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