<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook Dashboard</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }

        .navbar {
            background: #1a1a2e; color: white; padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar h1 { font-size: 20px; letter-spacing: 1px; }
        .navbar span { font-size: 13px; opacity: 0.6; }

        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }

        /* Stats Cards */
        .stats { display: grid; grid-template-columns: repeat(4, 1fr); gap: 16px; margin-bottom: 28px; }
        .card {
            background: white; border-radius: 10px; padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); border-left: 4px solid #ccc;
        }
        .card.total  { border-color: #4361ee; }
        .card.pending  { border-color: #f4a261; }
        .card.processed { border-color: #2ec4b6; }
        .card.failed   { border-color: #e63946; }
        .card .num { font-size: 32px; font-weight: 700; margin-bottom: 4px; }
        .card .label { font-size: 13px; color: #888; text-transform: uppercase; letter-spacing: 1px; }
        .card.total .num    { color: #4361ee; }
        .card.pending .num  { color: #f4a261; }
        .card.processed .num { color: #2ec4b6; }
        .card.failed .num   { color: #e63946; }

        /* Alert */
        .alert { background: #d4edda; color: #155724; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; }

        /* Filter Form */
        .filter-box {
            background: white; border-radius: 10px; padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 24px;
        }
        .filter-box form { display: flex; gap: 12px; flex-wrap: wrap; align-items: flex-end; }
        .filter-box label { font-size: 12px; color: #666; display: block; margin-bottom: 4px; }
        .filter-box select, .filter-box input {
            padding: 8px 12px; border: 1px solid #ddd; border-radius: 6px;
            font-size: 14px; min-width: 140px;
        }
        .btn { padding: 9px 20px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; }
        .btn-primary { background: #4361ee; color: white; }
        .btn-secondary { background: #eee; color: #555; text-decoration: none; display: inline-block; }
        .btn:hover { opacity: 0.88; }

        /* Table */
        .table-box {
            background: white; border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead { background: #1a1a2e; color: white; }
        th { padding: 13px 16px; text-align: left; font-size: 13px; letter-spacing: 0.5px; }
        td { padding: 12px 16px; font-size: 14px; border-bottom: 1px solid #f0f0f0; }
        tr:last-child td { border-bottom: none; }
        tr:hover td { background: #fafbff; }

        /* Badges */
        .badge {
            padding: 3px 10px; border-radius: 20px; font-size: 12px; font-weight: 600;
        }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-processed { background: #d1f2eb; color: #0e6655; }
        .badge-failed    { background: #fde8e8; color: #c0392b; }
        .badge-source    { background: #e8eaf6; color: #3949ab; }

        /* Actions */
        .actions { display: flex; gap: 8px; }
        .btn-sm { padding: 5px 12px; font-size: 12px; border-radius: 5px; border: none; cursor: pointer; font-weight: 600; }
        .btn-view   { background: #e8f4fd; color: #2980b9; text-decoration: none; }
        .btn-replay { background: #fef9e7; color: #d68910; }
        .btn-replay:hover { background: #f4d03f; color: #333; }

        /* Pagination */
        .pagination { padding: 16px 20px; display: flex; gap: 6px; justify-content: flex-end; }
        .pagination a, .pagination span {
            padding: 6px 12px; border-radius: 5px; font-size: 13px;
            border: 1px solid #ddd; color: #4361ee; text-decoration: none;
        }
        .pagination span.active { background: #4361ee; color: white; border-color: #4361ee; }

        .empty { text-align: center; padding: 40px; color: #aaa; font-size: 15px; }
    </style>
</head>
<body>

<div class="navbar">
    <h1>🔔 Webhook Dashboard</h1>
    <span>Laravel 12 · Webhook Monitor</span>
</div>

<div class="container">

    @if(session('success'))
        <div class="alert">✅ {{ session('success') }}</div>
    @endif

    {{-- Stats --}}
    <div class="stats">
        <div class="card total">
            <div class="num">{{ $stats['total'] }}</div>
            <div class="label">Total Webhooks</div>
        </div>
        <div class="card pending">
            <div class="num">{{ $stats['pending'] }}</div>
            <div class="label">Pending</div>
        </div>
        <div class="card processed">
            <div class="num">{{ $stats['processed'] }}</div>
            <div class="label">Processed</div>
        </div>
        <div class="card failed">
            <div class="num">{{ $stats['failed'] }}</div>
            <div class="label">Failed</div>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-box">
        <form method="GET" action="{{ route('webhook.dashboard') }}">
            <div>
                <label>Status</label>
                <select name="status">
                    <option value="">All Status</option>
                    <option value="pending"   {{ request('status') == 'pending'   ? 'selected' : '' }}>Pending</option>
                    <option value="processed" {{ request('status') == 'processed' ? 'selected' : '' }}>Processed</option>
                    <option value="failed"    {{ request('status') == 'failed'    ? 'selected' : '' }}>Failed</option>
                </select>
            </div>
            <div>
                <label>Source</label>
                <select name="source">
                    <option value="">All Sources</option>
                    <option value="general"  {{ request('source') == 'general'  ? 'selected' : '' }}>General</option>
                    <option value="stripe"   {{ request('source') == 'stripe'   ? 'selected' : '' }}>Stripe</option>
                    <option value="razorpay" {{ request('source') == 'razorpay' ? 'selected' : '' }}>Razorpay</option>
                    <option value="whatsapp" {{ request('source') == 'whatsapp' ? 'selected' : '' }}>WhatsApp</option>
                </select>
            </div>
            <div>
                <label>Event Type</label>
                <input type="text" name="event_type" value="{{ request('event_type') }}" placeholder="e.g. order.created">
            </div>
            <div>
                <label>Date</label>
                <input type="date" name="date" value="{{ request('date') }}">
            </div>
            <div>
                <button type="submit" class="btn btn-primary">🔍 Filter</button>
                <a href="{{ route('webhook.dashboard') }}" class="btn btn-secondary">✖ Clear</a>
            </div>
        </form>
    </div>

    {{-- Table --}}
    <div class="table-box">
        <table>
            <thead>
                <tr>
                    <th>#ID</th>
                    <th>Source</th>
                    <th>Event Type</th>
                    <th>Status</th>
                    <th>Retries</th>
                    <th>Received At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr>
                    <td><strong>#{{ $log->id }}</strong></td>
                    <td><span class="badge badge-source">{{ $log->source }}</span></td>
                    <td>{{ $log->event_type ?? '—' }}</td>
                    <td>
                        <span class="badge badge-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                    </td>
                    <td>{{ $log->retry_count }}</td>
                    <td>{{ $log->created_at->format('d M Y, h:i A') }}</td>
                    <td>
                        <div class="actions">
                            <a href="{{ route('webhook.show', $log->id) }}" class="btn-sm btn-view">👁 View</a>
                            <form method="POST" action="{{ route('webhook.replay', $log->id) }}" style="display:inline">
                                @csrf
                                <button type="submit" class="btn-sm btn-replay">🔄 Replay</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="empty">No webhook logs found.</td>
                </tr>
                @endforelse
            </tbody>
        </table>

        <div class="pagination">
            {{ $logs->links() }}
        </div>
    </div>

</div>
</body>
</html>
