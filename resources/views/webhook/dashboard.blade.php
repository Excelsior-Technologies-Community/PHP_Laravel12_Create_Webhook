<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Webhook Dashboard</title>

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

        /* =========================
           Navbar
        ========================= */

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
            letter-spacing: 1px;
        }

        .navbar span {
            font-size: 13px;
            opacity: 0.6;
        }

        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .analytics-link {
            color: white;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .analytics-link:hover {
            opacity: 0.8;
        }

        /* =========================
           Container
        ========================= */

        .container {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* =========================
           Stats Cards
        ========================= */

        .stats {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 16px;
            margin-bottom: 28px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            border-left: 4px solid #ccc;
        }

        .card.total {
            border-color: #4361ee;
        }

        .card.pending {
            border-color: #f4a261;
        }

        .card.processed {
            border-color: #2ec4b6;
        }

        .card.failed {
            border-color: #e63946;
        }

        .card.duplicates {
            border-color: #7b2cbf;
        }

        .card .num {
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .card .label {
            font-size: 13px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .card.total .num {
            color: #4361ee;
        }

        .card.pending .num {
            color: #f4a261;
        }

        .card.processed .num {
            color: #2ec4b6;
        }

        .card.failed .num {
            color: #e63946;
        }

        .card.duplicates .num {
            color: #7b2cbf;
        }

        /* =========================
           Alert
        ========================= */

        .alert {
            background: #d4edda;
            color: #155724;
            padding: 12px 18px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        /* =========================
           Filter Form
        ========================= */

        .filter-box {
            background: white;
            border-radius: 10px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            margin-bottom: 24px;
        }

        .filter-box form {
            display: flex;
            gap: 12px;
            flex-wrap: wrap;
            align-items: flex-end;
        }

        .filter-box label {
            font-size: 12px;
            color: #666;
            display: block;
            margin-bottom: 4px;
        }

        .filter-box select,
        .filter-box input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
            min-width: 140px;
            background: white;
        }

        .filter-box select:focus,
        .filter-box input:focus {
            outline: none;
            border-color: #4361ee;
        }

        /* =========================
           Buttons
        ========================= */

        .btn {
            padding: 9px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
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
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            opacity: 0.88;
        }

        /* =========================
           Table
        ========================= */

        .table-box {
            background: white;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            overflow: hidden;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        thead {
            background: #1a1a2e;
            color: white;
        }

        th {
            padding: 13px 16px;
            text-align: left;
            font-size: 13px;
            letter-spacing: 0.5px;
            white-space: nowrap;
        }

        td {
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid #f0f0f0;
            white-space: nowrap;
        }

        tr:last-child td {
            border-bottom: none;
        }

        tr:hover td {
            background: #fafbff;
        }

        /* Duplicate row */

        tr.duplicate-row td {
            background: #faf5ff;
        }

        tr.duplicate-row:hover td {
            background: #f3e8ff;
        }

        /* =========================
           Badges
        ========================= */

        .badge {
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            display: inline-block;
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

        /* =========================
           Actions
        ========================= */

        .actions {
            display: flex;
            gap: 8px;
        }

        .btn-sm {
            padding: 5px 12px;
            font-size: 12px;
            border-radius: 5px;
            border: none;
            cursor: pointer;
            font-weight: 600;
        }

        .btn-view {
            background: #e8f4fd;
            color: #2980b9;
            text-decoration: none;
        }

        .btn-replay {
            background: #fef9e7;
            color: #d68910;
        }

        .btn-replay:hover {
            background: #f4d03f;
            color: #333;
        }

        /* =========================
           Pagination
        ========================= */

        .pagination {
            padding: 16px 20px;
            display: flex;
            gap: 6px;
            justify-content: flex-end;
        }

        .pagination a,
        .pagination span {
            padding: 6px 12px;
            border-radius: 5px;
            font-size: 13px;
            border: 1px solid #ddd;
            color: #4361ee;
            text-decoration: none;
        }

        .pagination span.active {
            background: #4361ee;
            color: white;
            border-color: #4361ee;
        }

        /* =========================
           Empty State
        ========================= */

        .empty {
            text-align: center;
            padding: 40px;
            color: #aaa;
            font-size: 15px;
        }

        /* =========================
           Responsive
        ========================= */

        @media (max-width: 1100px) {

            .stats {
                grid-template-columns: repeat(3, 1fr);
            }

        }

        @media (max-width: 900px) {

            .stats {
                grid-template-columns: repeat(2, 1fr);
            }

            .navbar {
                padding: 14px 20px;
            }

            .navbar-actions {
                gap: 12px;
            }

        }

        @media (max-width: 600px) {

            .stats {
                grid-template-columns: 1fr;
            }

            .container {
                padding: 0 12px;
                margin-top: 20px;
            }

            .navbar {
                padding: 14px 16px;
            }

            .navbar h1 {
                font-size: 17px;
            }

            .navbar span {
                display: none;
            }

            .analytics-link {
                font-size: 13px;
            }

            .filter-box {
                padding: 16px;
            }

            .filter-box form {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-box form>div {
                width: 100%;
            }

            .filter-box select,
            .filter-box input {
                width: 100%;
            }

            .filter-box .btn {
                width: 100%;
                margin-bottom: 6px;
            }

            .table-box {
                overflow-x: auto;
            }

            table {
                min-width: 950px;
            }

        }
    </style>

</head>

<body>

    <div class="navbar">

        <h1>🔔 Webhook Dashboard</h1>

        <div class="navbar-actions">

            <a
                href="{{ route('webhook.analytics') }}"
                class="analytics-link">
                📊 Analytics
            </a>

            <span>
                Laravel 12 · Webhook Monitor
            </span>

        </div>

    </div>


    <div class="container">

        {{-- Success Alert --}}
        @if(session('success'))

        <div class="alert">
            ✅ {{ session('success') }}
        </div>

        @endif

        @if(session('error'))

        <div
            class="alert"
            style="
            background:#fde8e8;
            color:#c0392b;
        ">
            ❌ {{ session('error') }}
        </div>

        @endif


        {{-- =========================
         Stats
    ========================== --}}

        <div class="stats">

            <div class="card total">

                <div class="num">
                    {{ $stats['total'] }}
                </div>

                <div class="label">
                    Total Webhooks
                </div>

            </div>


            <div class="card pending">

                <div class="num">
                    {{ $stats['pending'] }}
                </div>

                <div class="label">
                    Pending
                </div>

            </div>


            <div class="card processed">

                <div class="num">
                    {{ $stats['processed'] }}
                </div>

                <div class="label">
                    Processed
                </div>

            </div>


            <div class="card failed">

                <div class="num">
                    {{ $stats['failed'] }}
                </div>

                <div class="label">
                    Failed
                </div>

            </div>


            <div class="card duplicates">

                <div class="num">
                    {{ $stats['duplicates'] }}
                </div>

                <div class="label">
                    Duplicate Webhooks
                </div>

            </div>

        </div>


        {{-- =========================
         Filters
    ========================== --}}

        <div class="filter-box">

            <form
                method="GET"
                action="{{ route('webhook.dashboard') }}">

                {{-- Status --}}

                <div>

                    <label>
                        Status
                    </label>

                    <select name="status">

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="pending"
                            {{ request('status') == 'pending' ? 'selected' : '' }}>
                            Pending
                        </option>

                        <option
                            value="processed"
                            {{ request('status') == 'processed' ? 'selected' : '' }}>
                            Processed
                        </option>

                        <option
                            value="failed"
                            {{ request('status') == 'failed' ? 'selected' : '' }}>
                            Failed
                        </option>

                    </select>

                </div>


                {{-- Source --}}

                <div>

                    <label>
                        Source
                    </label>

                    <select name="source">

                        <option value="">
                            All Sources
                        </option>

                        <option
                            value="general"
                            {{ request('source') == 'general' ? 'selected' : '' }}>
                            General
                        </option>

                        <option
                            value="stripe"
                            {{ request('source') == 'stripe' ? 'selected' : '' }}>
                            Stripe
                        </option>

                        <option
                            value="razorpay"
                            {{ request('source') == 'razorpay' ? 'selected' : '' }}>
                            Razorpay
                        </option>

                        <option
                            value="whatsapp"
                            {{ request('source') == 'whatsapp' ? 'selected' : '' }}>
                            WhatsApp
                        </option>

                    </select>

                </div>


                {{-- Event Type --}}

                <div>

                    <label>
                        Event Type
                    </label>

                    <input
                        type="text"
                        name="event_type"
                        value="{{ request('event_type') }}"
                        placeholder="e.g. order.created">

                </div>


                {{-- Date --}}

                <div>

                    <label>
                        Date
                    </label>

                    <input
                        type="date"
                        name="date"
                        value="{{ request('date') }}">

                </div>


                {{-- Duplicate --}}

                <div>

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


                {{-- Buttons --}}

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


        {{-- =========================
         Table
    ========================== --}}

        <div class="table-box">

            <div class="table-wrapper">

                <table>

                    <thead>

                        <tr>

                            <th>
                                #ID
                            </th>

                            <th>
                                Source
                            </th>

                            <th>
                                Event Type
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
                                Received At
                            </th>

                            <th>
                                Actions
                            </th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logs as $log)

                        <tr class="{{ $log->is_duplicate ? 'duplicate-row' : '' }}">

                            {{-- ID --}}

                            <td>

                                <strong>
                                    #{{ $log->id }}
                                </strong>

                            </td>


                            {{-- Source --}}

                            <td>

                                <span class="badge badge-source">
                                    {{ $log->source }}
                                </span>

                            </td>


                            {{-- Event Type --}}

                            <td>
                                {{ $log->event_type ?? '—' }}
                            </td>


                            {{-- Status --}}

                            <td>

                                <span class="badge badge-{{ $log->status }}">

                                    {{ ucfirst($log->status) }}

                                </span>

                            </td>


                            {{-- Duplicate Type --}}

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


                            {{-- Retries --}}

                            <td>
                                {{ $log->retry_count }}
                            </td>


                            {{-- Received At --}}

                            <td>

                                {{ $log->created_at->format('d M Y, h:i A') }}

                            </td>


                            {{-- Actions --}}

                            <td>

                                <div class="actions">

                                    <a
                                        href="{{ route('webhook.show', $log->id) }}"
                                        class="btn-sm btn-view">
                                        👁 View
                                    </a>


                                    @if(!$log->is_duplicate)

                                    <form
                                        method="POST"
                                        action="{{ route('webhook.replay', $log->id) }}"
                                        style="display:inline">

                                        @csrf

                                        <button
                                            type="submit"
                                            class="btn-sm btn-replay">
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
                                No webhook logs found.
                            </td>

                        </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>


            {{-- Pagination --}}

            <div class="pagination">

                {{ $logs->links() }}

            </div>

        </div>

    </div>

</body>

</html>