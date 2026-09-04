<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Webhook #{{ $log->id }} Detail</title>

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
        }

        .navbar a {
            color: #aab4ff;
            text-decoration: none;
            font-size: 14px;
        }

        .navbar a:hover {
            color: white;
        }

        /* =========================
       Container
    ========================= */

        .container {
            max-width: 900px;
            margin: 30px auto;
            padding: 0 20px;
        }

        /* =========================
       Cards
    ========================= */

        .card {
            background: white;
            border-radius: 10px;
            padding: 28px 32px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.07);
            margin-bottom: 20px;
        }

        .card h2 {
            font-size: 17px;
            margin-bottom: 18px;
            color: #1a1a2e;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }

        /* =========================
       Meta Grid
    ========================= */

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }

        .meta-item label {
            font-size: 12px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            display: block;
            margin-bottom: 4px;
        }

        .meta-item span {
            font-size: 15px;
            font-weight: 600;
        }

        /* =========================
       Badges
    ========================= */

        .badge {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 13px !important;
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
            background: #eee8ff;
            color: #6c3483;
        }

        .badge-original {
            background: #d1f2eb;
            color: #0e6655;
        }

        /* =========================
       Duplicate Information
    ========================= */

        .duplicate-box {
            background: #eee8ff;
            border-left: 4px solid #7b2cbf;
            padding: 14px 18px;
            border-radius: 6px;
            color: #6c3483;
            font-size: 14px;
            line-height: 1.6;
        }

        .duplicate-box a {
            color: #4361ee;
            font-weight: 600;
            text-decoration: none;
        }

        .duplicate-box a:hover {
            text-decoration: underline;
        }

        /* =========================
       Error Message
    ========================= */

        .error-box {
            background: #fde8e8;
            border-left: 4px solid #e63946;
            padding: 14px 18px;
            border-radius: 6px;
            color: #c0392b;
            font-size: 14px;
            line-height: 1.6;
        }

        /* =========================
       Payload
    ========================= */

        pre {
            background: #1e1e2e;
            color: #cdd6f4;
            padding: 20px;
            border-radius: 8px;
            font-size: 14px;
            overflow-x: auto;
            line-height: 1.6;
            white-space: pre-wrap;
            word-break: break-word;
        }

        /* =========================
       Actions
    ========================= */

        .actions {
            display: flex;
            gap: 12px;
            margin-top: 10px;
            align-items: center;
        }

        .btn {
            padding: 10px 22px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            display: inline-block;
        }

        .btn-back {
            background: #eee;
            color: #555;
        }

        .btn-replay {
            background: #f4d03f;
            color: #333;
        }

        .btn:hover {
            opacity: 0.85;
        }

        /* =========================
       Duplicate Replay Notice
    ========================= */

        .no-replay {
            background: #f8f5ff;
            color: #6c3483;
            border: 1px solid #e3d5ff;
            padding: 10px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
        }

        /* =========================
       Responsive
    ========================= */

        @media (max-width: 700px) {

            .navbar {
                padding: 14px 18px;
                gap: 12px;
            }

            .navbar h1 {
                font-size: 17px;
            }

            .navbar a {
                font-size: 13px;
            }

            .container {
                margin: 20px auto;
                padding: 0 12px;
            }

            .card {
                padding: 20px;
            }

            .meta-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }

            .actions {
                flex-direction: column;
                align-items: stretch;
            }

            .actions .btn,
            .actions form {
                width: 100%;
            }

            .actions .btn {
                text-align: center;
            }

        }
    </style>
    

</head>

<body>

    <div class="navbar">

        
        <h1>
            🔔 Webhook #{{ $log->id }} Detail
        </h1>

        <a href="{{ route('webhook.dashboard') }}">
            ← Back to Dashboard
        </a>
       

    </div>

    <div class="container">

      
        {{-- =========================
     Meta Info
========================== --}}

        <div class="card">

            <h2>
                📋 Webhook Info
            </h2>

            <div class="meta-grid">


                {{-- Database ID --}}

                <div class="meta-item">

                    <label>
                        ID
                    </label>

                    <span>
                        #{{ $log->id }}
                    </span>

                </div>


                {{-- Source --}}

                <div class="meta-item">

                    <label>
                        Source
                    </label>

                    <span class="badge badge-source">
                        {{ $log->source }}
                    </span>

                </div>


                {{-- Event Type --}}

                <div class="meta-item">

                    <label>
                        Event Type
                    </label>

                    <span>
                        {{ $log->event_type ?? '—' }}
                    </span>

                </div>


                {{-- Webhook ID --}}

                <div class="meta-item">

                    <label>
                        Webhook ID
                    </label>

                    <span>
                        {{ $log->webhook_id ?? '—' }}
                    </span>

                </div>


                {{-- Duplicate Status --}}

                <div class="meta-item">

                    <label>
                        Duplicate
                    </label>

                    @if($log->is_duplicate)

                    <span class="badge badge-duplicate">
                        🔁 Yes
                    </span>

                    @else

                    <span class="badge badge-original">
                        ✓ No
                    </span>

                    @endif

                </div>


                {{-- Status --}}

                <div class="meta-item">

                    <label>
                        Status
                    </label>

                    <span class="badge badge-{{ $log->status }}">
                        {{ ucfirst($log->status) }}
                    </span>

                </div>


                {{-- Retry Count --}}

                <div class="meta-item">

                    <label>
                        Retry Count
                    </label>

                    <span>
                        {{ $log->retry_count }}
                    </span>

                </div>


                {{-- Received At --}}

                <div class="meta-item">

                    <label>
                        Received At
                    </label>

                    <span>
                        {{ $log->created_at->format('d M Y, h:i:s A') }}
                    </span>

                </div>


                {{-- Processed At --}}

                <div class="meta-item">

                    <label>
                        Processed At
                    </label>

                    <span>
                        {{ $log->processed_at
                    ? $log->processed_at->format('d M Y, h:i:s A')
                    : '—'
                }}
                    </span>

                </div>

            </div>

        </div>


        {{-- =========================
     Duplicate Information
========================== --}}

        @if($log->is_duplicate)

        <div class="card">

            <h2>
                🔁 Duplicate Webhook
            </h2>

            <div class="duplicate-box">

                This webhook was detected as a duplicate delivery.

                @if($log->duplicate_of)

                <br>
                <br>

                <strong>
                    Original Webhook:
                </strong>

                <a
                    href="{{ route('webhook.show', $log->duplicate_of) }}">
                    #{{ $log->duplicate_of }}
                </a>

                @endif

            </div>

        </div>

        @endif


        {{-- =========================
     Error Message
========================== --}}

        @if($log->error_message)

        <div class="card">

            <h2>
                ❌ Error Message
            </h2>

            <div class="error-box">
                {{ $log->error_message }}
            </div>

        </div>

        @endif


        {{-- =========================
     Payload
========================== --}}

        <div class="card">

            <h2>
                📦 Payload
            </h2>

            <pre>{{ json_encode(
$log->payload,
JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE


) }}</pre>

           
        </div>


        {{-- =========================
     Actions
========================== --}}

        <div class="actions">

            <a
                href="{{ route('webhook.dashboard') }}"
                class="btn btn-back">
                ← Back
            </a>


            {{-- Do not replay duplicate webhooks --}}

            @if(!$log->is_duplicate)

            <form
                method="POST"
                action="{{ route('webhook.replay', $log->id) }}">

                @csrf

                <button
                    type="submit"
                    class="btn btn-replay">
                    🔄 Replay This Webhook
                </button>

            </form>

            @else

            <div class="no-replay">
                🔒 Duplicate webhooks cannot be replayed.
            </div>

            @endif

        </div>

    </div>

</body>

</html>