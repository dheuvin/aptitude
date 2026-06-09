<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Aptitude Test Portal')</title>
    <style>
        :root {
            --bg: #f3efe7;
            --surface: rgba(255, 255, 255, 0.92);
            --surface-strong: #fffdf8;
            --text: #1f2937;
            --muted: #6b7280;
            --accent: #0f766e;
            --accent-strong: #115e59;
            --accent-soft: #d1fae5;
            --danger: #b91c1c;
            --danger-soft: #fee2e2;
            --warning: #92400e;
            --warning-soft: #fef3c7;
            --border: rgba(15, 23, 42, 0.08);
            --shadow: 0 24px 60px rgba(15, 23, 42, 0.08);
            --radius: 20px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            color: var(--text);
            background:
                radial-gradient(circle at top left, rgba(15, 118, 110, 0.16), transparent 30%),
                radial-gradient(circle at right, rgba(217, 119, 6, 0.14), transparent 25%),
                linear-gradient(180deg, #fdf7ed 0%, #f7f4ee 50%, #f0ebe2 100%);
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .shell {
            width: min(1180px, calc(100% - 32px));
            margin: 0 auto;
            padding: 28px 0 48px;
        }

        .nav {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            align-items: center;
            margin-bottom: 28px;
            padding: 16px 20px;
            border: 1px solid var(--border);
            border-radius: 22px;
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(12px);
            box-shadow: var(--shadow);
        }

        .brand {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .brand strong {
            font-size: 1.05rem;
            letter-spacing: 0.03em;
        }

        .brand span,
        .meta,
        .empty,
        .help,
        .hint {
            color: var(--muted);
        }

        .nav-links {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
        }

        .link-pill,
        .button,
        button,
        input,
        select,
        textarea {
            font: inherit;
        }

        .link-pill,
        .button,
        button {
            border: 0;
            border-radius: 999px;
            padding: 11px 18px;
            cursor: pointer;
            transition: transform 0.18s ease, box-shadow 0.18s ease, background 0.18s ease;
        }

        .link-pill,
        .button.secondary {
            background: rgba(255, 255, 255, 0.7);
            border: 1px solid var(--border);
        }

        .button.primary,
        button.primary {
            color: white;
            background: linear-gradient(135deg, var(--accent) 0%, var(--accent-strong) 100%);
            box-shadow: 0 10px 24px rgba(15, 118, 110, 0.22);
        }

        .button.danger,
        button.danger {
            color: white;
            background: linear-gradient(135deg, #dc2626 0%, #991b1b 100%);
            box-shadow: 0 10px 24px rgba(220, 38, 38, 0.18);
        }

        .link-pill:hover,
        .button:hover,
        button:hover {
            transform: translateY(-1px);
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 16px;
            margin-bottom: 24px;
        }

        .page-header h1,
        .page-header h2,
        .page-header h3,
        .card h2,
        .card h3,
        .card h4 {
            margin: 0;
        }

        .card,
        .table-wrap,
        .hero {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            box-shadow: var(--shadow);
        }

        .card,
        .hero {
            padding: 24px;
        }

        .grid {
            display: grid;
            gap: 18px;
        }

        .grid.stats {
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        }

        .grid.cards {
            grid-template-columns: repeat(auto-fit, minmax(290px, 1fr));
        }

        .stat {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .stat strong {
            font-size: 2rem;
            line-height: 1;
        }

        .flash,
        .error-box {
            margin-bottom: 18px;
            padding: 14px 16px;
            border-radius: 16px;
            border: 1px solid transparent;
        }

        .flash.success {
            background: var(--accent-soft);
            border-color: rgba(15, 118, 110, 0.15);
            color: var(--accent-strong);
        }

        .flash.error,
        .error-box {
            background: var(--danger-soft);
            border-color: rgba(185, 28, 28, 0.14);
            color: var(--danger);
        }

        .flash.warning {
            background: var(--warning-soft);
            border-color: rgba(146, 64, 14, 0.14);
            color: var(--warning);
        }

        .badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 7px 12px;
            border-radius: 999px;
            font-size: 0.9rem;
            font-weight: 600;
            background: rgba(15, 118, 110, 0.08);
            color: var(--accent-strong);
        }

        .badge.warning {
            background: rgba(217, 119, 6, 0.13);
            color: #b45309;
        }

        .badge.danger {
            background: rgba(220, 38, 38, 0.1);
            color: #b91c1c;
        }

        .badge.neutral {
            background: rgba(15, 23, 42, 0.08);
            color: #334155;
        }

        .stack {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .inline {
            display: flex;
            gap: 10px;
            align-items: center;
            flex-wrap: wrap;
        }

        .meta-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 14px;
        }

        .meta-item {
            padding: 14px 16px;
            background: rgba(255, 255, 255, 0.72);
            border: 1px solid var(--border);
            border-radius: 16px;
        }

        form.stack {
            max-width: 720px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
        }

        input,
        select,
        textarea {
            width: 100%;
            border: 1px solid rgba(15, 23, 42, 0.12);
            background: rgba(255, 255, 255, 0.96);
            border-radius: 14px;
            padding: 13px 14px;
            color: var(--text);
        }

        textarea {
            min-height: 130px;
            resize: vertical;
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .field-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .table-wrap {
            overflow: hidden;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 16px 18px;
            border-bottom: 1px solid rgba(15, 23, 42, 0.08);
            text-align: left;
            vertical-align: top;
        }

        th {
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: var(--muted);
            background: rgba(248, 250, 252, 0.88);
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .actions {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .question-card {
            padding: 20px;
        }

        .question-options {
            display: grid;
            gap: 10px;
            margin-top: 14px;
        }

        .option {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            padding: 12px 14px;
            border: 1px solid rgba(15, 23, 42, 0.08);
            border-radius: 14px;
            background: rgba(255, 255, 255, 0.72);
            width: 100%;
            text-align: left;
        }

        label.option {
            margin-bottom: 0;
            cursor: pointer;
        }

        .option input[type="radio"] {
            width: auto;
            margin: 4px 0 0;
            padding: 0;
            border: 0;
            background: transparent;
            flex-shrink: 0;
        }

        .option-body {
            flex: 1;
            line-height: 1.5;
            font-weight: 400;
        }


        .option.correct {
            border-color: rgba(15, 118, 110, 0.24);
            background: rgba(209, 250, 229, 0.7);
        }

        .option.wrong {
            border-color: rgba(220, 38, 38, 0.22);
            background: rgba(254, 226, 226, 0.7);
        }

        .timer {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--accent-strong);
        }

        .timer.danger {
            color: var(--danger);
        }

        .pagination-wrap {
            margin-top: 20px;
            display: flex;
            justify-content: center;
        }

        .login-shell {
            width: min(520px, calc(100% - 32px));
            margin: 60px auto;
        }

        .center {
            text-align: center;
        }

        .spaced {
            justify-content: space-between;
        }

        @media (max-width: 768px) {
            .shell {
                width: min(100% - 20px, 1180px);
                padding-top: 18px;
            }

            .nav,
            .page-header {
                flex-direction: column;
                align-items: stretch;
            }

            th,
            td {
                padding: 14px 12px;
            }
        }
    </style>
</head>

<body>
    @hasSection('standalone')
        @yield('standalone')
    @else
        <div class="shell">
            @if (session()->has('user_id'))
                <nav class="nav">
                    <div class="brand">
                        <strong>Aptitude Test Portal</strong>
                        <span>{{ session('role') === 'admin' ? 'Admin control panel' : 'Candidate workspace' }}</span>
                    </div>

                    <div class="nav-links">
                        @if (session('role') === 'admin')
                            <a class="link-pill" href="{{ route('admin.dashboard') }}">Dashboard</a>
                            <a class="link-pill" href="{{ route('admin.users.index') }}">Users</a>
                            <a class="link-pill" href="{{ route('admin.tests.index') }}">Tests</a>
                            <a class="link-pill" href="{{ route('admin.results.index') }}">Attempts</a>
                        @elseif(session('role') === 'user')
                            <a class="link-pill" href="{{ route('user.dashboard') }}">My Tests</a>
                        @endif

                        <span class="badge neutral">{{ session('phone') }}</span>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="button secondary">Logout</button>
                        </form>
                    </div>
                </nav>
            @endif

            @if (session('success'))
                <div class="flash success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="flash error">{{ session('error') }}</div>
            @endif

            @if (session('generated_password'))
                <div class="flash warning">
                    Generated password for the new user: <strong>{{ session('generated_password') }}</strong>
                </div>
            @endif

            @if ($errors->any())
                <div class="error-box">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @yield('content')
            @yield('scripts')
        </div>
    @endif
</body>

</html>
