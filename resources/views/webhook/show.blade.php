<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>
        Webhook #{{ $log->id }} Detail
    </title>

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

        .navbar {
            background: #17172b;
            color: white;
            padding: 18px 35px;

            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .navbar h1 {
            font-size: 20px;
        }

        .navbar a {
            color: #aab4ff;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .container {
            max-width: 950px;
            margin: 30px auto;
            padding: 0 20px;
        }

        .card {
            background: white;
            border-radius: 10px;
            padding: 26px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .07);
        }

        .card h2 {
            color: #17172b;
            font-size: 18px;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #eee;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .meta-item label {
            display: block;
            color: #888;
            font-size: 11px;
            text-transform: uppercase;
            margin-bottom: 6px;
        }

        .meta-item span {
            font-size: 15px;
            font-weight: 600;
        }

        .badge {
            display: inline-block;
            padding: 5px 11px;
            border-radius: 20px;
            font-size: 12px !important;
            font-weight: 700 !important;
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

        .duplicate-box {
            background: #f6efff;
            border-left: 4px solid #7b2cbf;
            padding: 16px;
            border-radius: 7px;
            color: #6c3483;
            line-height: 1.7;
        }

        .duplicate-box a {
            color: #4361ee;
            text-decoration: none;
            font-weight: 700;
        }

        .error-box {
            background: #fde8e8;
            border-left: 4px solid #e63946;
            padding: 16px;
            border-radius: 7px;
            color: #c0392b;
            line-height: 1.6;
        }

        pre {
            background: #1e1e2e;
            color: #cdd6f4;
            padding: 20px;
            border-radius: 8px;
            overflow-x: auto;
            white-space: pre-wrap;
            word-break: break-word;
            line-height: 1.6;
            font-size: 13px;
        }

        .actions {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
        }

        .btn {
            padding: 10px 18px;
            border-radius: 6px;
            text-decoration: none;
            border: none;
            cursor: pointer;
            font-size: 13px;
            font-weight: 600;
        }

        .btn-back {
            background: #e9ecef;
            color: #555;
        }

        .btn-replay {
            background: #f4d03f;
            color: #333;
        }

        .no-replay {
            padding: 10px 15px;
            border-radius: 6px;
            background: #f6efff;
            color: #6c3483;
            font-size: 13px;
            font-weight: 600;
        }

        @media(max-width:700px) {

            .navbar {
                padding: 15px;
            }

            .navbar h1 {
                font-size: 17px;
            }

            .container {
                padding: 0 12px;
            }

            .card {
                padding: 20px;
            }

            .meta-grid {
                grid-template-columns: 1fr;
            }

            .actions {
                flex-direction: column;
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


    <!-- Navbar -->

    <div class="navbar">

        <h1>
            🔔 Webhook #{{ $log->id }} Detail
        </h1>

        <a href="{{ route('webhook.dashboard') }}">
            ← Dashboard
        </a>

    </div>


    <div class="container">


        <!-- Webhook Info -->

        <div class="card">

            <h2>
                📋 Webhook Information
            </h2>

            <div class="meta-grid">


                <div class="meta-item">

                    <label>
                        Database ID
                    </label>

                    <span>
                        #{{ $log->id }}
                    </span>

                </div>


                <div class="meta-item">

                    <label>
                        Source
                    </label>

                    <span class="badge badge-source">

                        {{ ucfirst($log->source) }}

                    </span>

                </div>


                <div class="meta-item">

                    <label>
                        Event Type
                    </label>

                    <span>
                        {{ $log->event_type ?? '—' }}
                    </span>

                </div>


                <div class="meta-item">

                    <label>
                        Webhook ID
                    </label>

                    <span>
                        {{ $log->webhook_id ?? '—' }}
                    </span>

                </div>


                <div class="meta-item">

                    <label>
                        Status
                    </label>

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

                </div>


                <div class="meta-item">

                    <label>
                        Type
                    </label>

                    @if($log->is_duplicate)

                    <span class="badge badge-duplicate">
                        Duplicate
                    </span>

                    @else

                    <span class="badge badge-original">
                        Original
                    </span>

                    @endif

                </div>


                <div class="meta-item">

                    <label>
                        Retry Count
                    </label>

                    <span>
                        {{ $log->retry_count }}
                    </span>

                </div>


                <div class="meta-item">

                    <label>
                        Received At
                    </label>

                    <span>
                        {{ $log->created_at->format('d M Y, h:i:s A') }}
                    </span>

                </div>


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


        <!-- Duplicate -->

        @if($log->is_duplicate)

        <div class="card">

            <h2>
                🔁 Duplicate Webhook
            </h2>

            <div class="duplicate-box">

                This webhook was detected as a duplicate delivery.

                @if($log->duplicate_of)

                <br><br>

                Original Webhook:

                <a
                    href="{{ route('webhook.show', $log->duplicate_of) }}">
                    #{{ $log->duplicate_of }}
                </a>

                @endif

            </div>

        </div>

        @endif


        <!-- Error -->

        @if($log->error_message)

        <div class="card">

            <h2>
                ❌ Error Information
            </h2>

            <div class="error-box">

                {{ $log->error_message }}

            </div>

        </div>

        @endif


        <!-- Payload -->

        <div class="card">

            <h2>
                📦 Webhook Payload
            </h2>

            <pre>{{ json_encode(
                $log->payload,
                JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
            ) }}</pre>

        </div>


        <!-- Actions -->

        <div class="actions">


            <a
                href="{{ route('webhook.dashboard') }}"
                class="btn btn-back">
                ← Back
            </a>


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