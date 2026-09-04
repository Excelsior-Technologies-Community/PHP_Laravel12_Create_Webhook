<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Webhook Dashboard</title>

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

        .navbar-right {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navbar-right a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
        }

        .navbar-right span {
            font-size: 13px;
            opacity: .65;
        }

        /* Container */

        .container {
            max-width: 1250px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* Alert */

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-weight: 600;
        }

        .alert-success {
            background: #d1f2eb;
            color: #0e6655;
        }

        .alert-error {
            background: #fde8e8;
            color: #c0392b;
        }

        /* Stats */

        .stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 15px;
            margin-bottom: 25px;
        }

        .stat-card {
            background: white;
            padding: 22px;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
            border-left: 5px solid #4361ee;
        }

        .stat-card.pending {
            border-color: #f4a261;
        }

        .stat-card.processed {
            border-color: #2ec4b6;
        }

        .stat-card.failed {
            border-color: #e63946;
        }

        .stat-card.duplicate {
            border-color: #7b2cbf;
        }

        .stat-number {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 5px;
        }

        .stat-label {
            color: #888;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .8px;
        }

        /* Filter */

        .filter-box {
            background: white;
            padding: 22px;
            border-radius: 10px;
            margin-bottom: 25px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        .filter-title {
            font-size: 17px;
            font-weight: 700;
            margin-bottom: 18px;
            color: #17172b;
        }

        .filter-form {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            align-items: end;
        }

        .form-group {
            flex: 1;
            min-width: 150px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            color: #777;
            margin-bottom: 6px;
            font-weight: 600;
        }

        input,
        select {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            background: white;
        }

        input:focus,
        select:focus {
            outline: none;
            border-color: #4361ee;
        }

        /* Buttons */

        .btn {
            padding: 10px 16px;
            border: none;
            border-radius: 6px;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            display: inline-block;
        }

        .btn-primary {
            background: #4361ee;
            color: white;
        }

        .btn-secondary {
            background: #eee;
            color: #555;
        }

        .btn-view {
            background: #e8f4fd;
            color: #2980b9;
        }

        .btn-replay {
            background: #fff3cd;
            color: #856404;
        }

        .btn:hover {
            opacity: .85;
        }

        /* Table */

        .table-box {
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        .table-header {
            padding: 20px;
            border-bottom: 1px solid #eee;
        }

        .table-header h2 {
            font-size: 18px;
            color: #17172b;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 950px;
        }

        thead {
            background: #17172b;
            color: white;
        }

        th {
            padding: 14px 15px;
            text-align: left;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }

        td {
            padding: 13px 15px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
        }

        tr:hover td {
            background: #fafbff;
        }

        .duplicate-row td {
            background: #faf5ff;
        }

        /* Badge */

        .badge {
            display: inline-block;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: 700;
        }

        .badge-pending {
            background: #fff3cd;
            color: #856404;
        }

        .badge-processed {
            background: #d1f2eb;
            color: #0e6655;
        }

        .badge-failed {
            background: #fde8e8;
            color: #c0392b;
        }

        .badge-source {
            background: #e8eaf6;
            color: #3949ab;
        }

        .badge-duplicate {
            background: #eee2ff;
            color: #7b2cbf;
        }

        .badge-original {
            background: #e8f5e9;
            color: #2e7d32;
        }

        /* Actions */

        .actions {
            display: flex;
            gap: 7px;
        }

        .btn-small {
            padding: 6px 10px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            text-decoration: none;
            font-size: 11px;
            font-weight: 600;
        }

        /* Pagination */

        .pagination-wrapper {
            padding: 18px;
            display: flex;
            justify-content: center;
        }

        .pagination-wrapper nav {
            display: flex;
        }

        .pagination-wrapper svg {
            width: 18px;
        }

        .pagination-wrapper a,
        .pagination-wrapper span {
            padding: 7px 11px;
            margin: 2px;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            font-size: 13px;
            color: #4361ee;
        }

        .pagination-wrapper span[aria-current="page"] {
            background: #4361ee;
            color: white;
        }

        /* Empty */

        .empty {
            text-align: center;
            padding: 50px;
            color: #999;
        }

        /* Responsive */

        @media(max-width: 1100px) {

            .stats {
                grid-template-columns: repeat(3, 1fr);
            }

        }

        @media(max-width: 700px) {

            .navbar {
                padding: 15px;
            }

            .navbar-right span {
                display: none;
            }

            .container {
                padding: 0 12px;
            }

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .filter-form {
                flex-direction: column;
                align-items: stretch;
            }

            .form-group {
                width: 100%;
            }

        }

        @media(max-width: 450px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .navbar h1 {
                font-size: 17px;
            }

        }
    </style>

</head>

<body>

    <!-- Navbar -->

    <div class="navbar">

        <h1>
            🔔 Webhook Dashboard
        </h1>

        <div class="navbar-right">

            <a href="{{ route('webhook.analytics') }}">
                📊 Analytics
            </a>

            <span>
                Laravel 12 · Webhook Monitor
            </span>

        </div>

    </div>


    <div class="container">

        <!-- Success -->

        @if(session('success'))

        <div class="alert alert-success">
            ✅ {{ session('success') }}
        </div>

        @endif


        <!-- Error -->

        @if(session('error'))

        <div class="alert alert-error">
            ❌ {{ session('error') }}
        </div>

        @endif


        <!-- Statistics -->

        <div class="stats">

            <div class="stat-card">

                <div class="stat-number">
                    {{ $stats['total'] }}
                </div>

                <div class="stat-label">
                    Total Webhooks
                </div>

            </div>


            <div class="stat-card pending">

                <div class="stat-number">
                    {{ $stats['pending'] }}
                </div>

                <div class="stat-label">
                    Pending
                </div>

            </div>


            <div class="stat-card processed">

                <div class="stat-number">
                    {{ $stats['processed'] }}
                </div>

                <div class="stat-label">
                    Processed
                </div>

            </div>


            <div class="stat-card failed">

                <div class="stat-number">
                    {{ $stats['failed'] }}
                </div>

                <div class="stat-label">
                    Failed
                </div>

            </div>


            <div class="stat-card duplicate">

                <div class="stat-number">
                    {{ $stats['duplicates'] }}
                </div>

                <div class="stat-label">
                    Duplicate Webhooks
                </div>

            </div>

        </div>


        <!-- Filters -->

        <div class="filter-box">

            <div class="filter-title">
                🔎 Search & Filter Webhooks
            </div>

            <form
                method="GET"
                action="{{ route('webhook.dashboard') }}"
                class="filter-form">

                <!-- Search -->

                <div class="form-group">

                    <label>
                        Search
                    </label>

                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="ID, webhook ID, source...">

                </div>


                <!-- Status -->

                <div class="form-group">

                    <label>
                        Status
                    </label>

                    <select name="status">

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="pending"
                            {{ request('status') === 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option
                            value="processed"
                            {{ request('status') === 'processed' ? 'selected' : '' }}>
                            Processed
                        </option>

                        <option
                            value="failed"
                            {{ request('status') === 'failed' ? 'selected' : '' }}>
                            Failed
                        </option>

                    </select>

                </div>


                <!-- Source -->

                <div class="form-group">

                    <label>
                        Source
                    </label>

                    <select name="source">

                        <option value="">
                            All Sources
                        </option>

                        <option
                            value="general"
                            {{ request('source') === 'general' ? 'selected' : '' }}>
                            General
                        </option>

                        <option
                            value="stripe"
                            {{ request('source') === 'stripe' ? 'selected' : '' }}>
                            Stripe
                        </option>

                        <option
                            value="razorpay"
                            {{ request('source') === 'razorpay' ? 'selected' : '' }}>
                            Razorpay
                        </option>

                        <option
                            value="whatsapp"
                            {{ request('source') === 'whatsapp' ? 'selected' : '' }}>
                            WhatsApp
                        </option>

                    </select>

                </div>


                <!-- Event -->

                <div class="form-group">

                    <label>
                        Event Type
                    </label>

                    <input
                        type="text"
                        name="event_type"
                        value="{{ request('event_type') }}"
                        placeholder="order.created">

                </div>


                <!-- Date -->

                <div class="form-group">

                    <label>
                        Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="{{ request('date') }}">

                </div>


                <!-- Duplicate -->

                <div class="form-group">

                    <label>
                        Duplicate
                    </label>

                    <select name="duplicate">

                        <option value="">
                            All Webhooks
                        </option>

                        <option
                            value="no"
                            {{ request('duplicate') === 'no' ? 'selected' : '' }}>
                            Original
                        </option>

                        <option
                            value="yes"
                            {{ request('duplicate') === 'yes' ? 'selected' : '' }}>
                            Duplicates
                        </option>

                    </select>

                </div>


                <!-- Filter -->

                <div>

                    <button
                        type="submit"
                        class="btn btn-primary">
                        🔍 Filter
                    </button>

                    <a
                        href="{{ route('webhook.dashboard') }}"
                        class="btn btn-secondary">
                        ✖ Clear
                    </a>

                </div>

            </form>

        </div>


        <!-- Table -->

        <div class="table-box">

            <div class="table-header">

                <h2>
                    📋 Webhook Logs
                </h2>

            </div>


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
                                Type
                            </th>

                            <th>
                                Retries
                            </th>

                            <th>
                                Received
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logs as $log)

                        <tr
                            class="{{ $log->is_duplicate ? 'duplicate-row' : '' }}">

                            <td>

                                <strong>
                                    #{{ $log->id }}
                                </strong>

                            </td>


                            <td>

                                <span class="badge badge-source">

                                    {{ ucfirst($log->source) }}

                                </span>

                            </td>


                            <td>

                                {{ $log->event_type ?? '—' }}

                            </td>


                            <td>

                                @if($log->is_duplicate)

                                <span class="badge badge-duplicate">
                                    🔁 Duplicate
                                </span>

                                @else

                                <span
                                    class="badge badge-{{ $log->status }}">
                                    {{ ucfirst($log->status) }}
                                </span>

                                @endif

                            </td>


                            <td>

                                @if($log->is_duplicate)

                                <span class="badge badge-duplicate">
                                    🔁 Duplicate
                                </span>

                                @else

                                <span class="badge badge-original">
                                    ✓ Original
                                </span>

                                @endif

                            </td>


                            <td>

                                {{ $log->retry_count }}

                            </td>


                            <td>

                                {{ $log->created_at->format('d M Y, h:i A') }}

                            </td>


                            <td>

                                <div class="actions">

                                    <a
                                        href="{{ route('webhook.show', $log->id) }}"
                                        class="btn-small btn-view">
                                        👁 View
                                    </a>


                                    @if(!$log->is_duplicate)

                                    <form
                                        method="POST"
                                        action="{{ route('webhook.replay', $log->id) }}">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn-small btn-replay">
                                            🔄 Replay
                                        </button>

                                    </form>

                                    @endif

                                </div>

                            </td>

                        </tr>

                        @empty

                        <tr>

                            <td
                                colspan="8"
                                class="empty">

                                📭 No webhook logs found.

                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            <!-- Pagination -->

            @if($logs->hasPages())

            <div class="pagination-wrapper">

                @if ($logs->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    <div class="custom-pagination">

                        @for ($page = 1; $page <= $logs->lastPage(); $page++)
                            <a href="{{ $logs->url($page) }}"
                                class="{{ $page == $logs->currentPage() ? 'active' : '' }}">
                                {{ $page }}
                            </a>
                            @endfor

                    </div>
                </div>
                @endif

            </div>

            @endif

        </div>

    </div>

</body>

</html>