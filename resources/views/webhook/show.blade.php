<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Webhook #{{ $log->id }} Detail</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Segoe UI', sans-serif; background: #f0f2f5; color: #333; }

        .navbar {
            background: #1a1a2e; color: white; padding: 16px 32px;
            display: flex; align-items: center; justify-content: space-between;
        }
        .navbar h1 { font-size: 20px; }
        .navbar a { color: #aab4ff; text-decoration: none; font-size: 14px; }

        .container { max-width: 900px; margin: 30px auto; padding: 0 20px; }

        .card {
            background: white; border-radius: 10px; padding: 28px 32px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.07); margin-bottom: 20px;
        }
        .card h2 { font-size: 17px; margin-bottom: 18px; color: #1a1a2e; border-bottom: 1px solid #eee; padding-bottom: 10px; }

        .meta-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .meta-item label { font-size: 12px; color: #888; text-transform: uppercase; letter-spacing: 0.5px; display: block; margin-bottom: 4px; }
        .meta-item span { font-size: 15px; font-weight: 600; }

        .badge { padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
        .badge-pending   { background: #fff3cd; color: #856404; }
        .badge-processed { background: #d1f2eb; color: #0e6655; }
        .badge-failed    { background: #fde8e8; color: #c0392b; }
        .badge-source    { background: #e8eaf6; color: #3949ab; }

        pre {
            background: #1e1e2e; color: #cdd6f4; padding: 20px; border-radius: 8px;
            font-size: 14px; overflow-x: auto; line-height: 1.6;
        }

        .error-box { background: #fde8e8; border-left: 4px solid #e63946; padding: 14px 18px; border-radius: 6px; color: #c0392b; font-size: 14px; }

        .actions { display: flex; gap: 12px; margin-top: 10px; }
        .btn { padding: 10px 22px; border: none; border-radius: 6px; cursor: pointer; font-size: 14px; font-weight: 600; text-decoration: none; display: inline-block; }
        .btn-back   { background: #eee; color: #555; }
        .btn-replay { background: #f4d03f; color: #333; }
        .btn:hover { opacity: 0.85; }
    </style>
</head>
<body>

<div class="navbar">
    <h1>🔔 Webhook #{{ $log->id }} Detail</h1>
    <a href="{{ route('webhook.dashboard') }}">← Back to Dashboard</a>
</div>

<div class="container">

    {{-- Meta Info --}}
    <div class="card">
        <h2>📋 Webhook Info</h2>
        <div class="meta-grid">
            <div class="meta-item">
                <label>ID</label>
                <span>#{{ $log->id }}</span>
            </div>
            <div class="meta-item">
                <label>Source</label>
                <span class="badge badge-source">{{ $log->source }}</span>
            </div>
            <div class="meta-item">
                <label>Event Type</label>
                <span>{{ $log->event_type ?? '—' }}</span>
            </div>
            <div class="meta-item">
                <label>Status</label>
                <span class="badge badge-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
            </div>
            <div class="meta-item">
                <label>Retry Count</label>
                <span>{{ $log->retry_count }}</span>
            </div>
            <div class="meta-item">
                <label>Received At</label>
                <span>{{ $log->created_at->format('d M Y, h:i:s A') }}</span>
            </div>
        </div>
    </div>

    {{-- Error Message --}}
    @if($log->error_message)
    <div class="card">
        <h2>❌ Error Message</h2>
        <div class="error-box">{{ $log->error_message }}</div>
    </div>
    @endif

    {{-- Payload --}}
    <div class="card">
        <h2>📦 Payload</h2>
        <pre>{{ json_encode($log->payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
    </div>

    {{-- Actions --}}
    <div class="actions">
        <a href="{{ route('webhook.dashboard') }}" class="btn btn-back">← Back</a>
        <form method="POST" action="{{ route('webhook.replay', $log->id) }}">
            @csrf
            <button type="submit" class="btn btn-replay">🔄 Replay This Webhook</button>
        </form>
    </div>

</div>
</body>
</html>
