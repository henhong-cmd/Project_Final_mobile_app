<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'Students')</title>

    <style>
        :root {
            color-scheme: light;
            font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
            color: #172033;
            background: #f4f7fb;
        }

        * {
            box-sizing: border-box;
        }

        body {
            min-height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #f8fbff 0%, #eef4fb 100%);
        }

        .container {
            width: min(100% - 2rem, 1100px);
            margin: 0 auto;
        }

        .py-4 {
            padding-top: 3rem;
            padding-bottom: 3rem;
        }

        h1 {
            margin: 0 0 1.5rem;
            color: #172033;
            font-size: clamp(2rem, 4vw, 2.75rem);
            letter-spacing: -0.04em;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 2.5rem;
            padding: 0.6rem 1rem;
            border: 0;
            border-radius: 0.65rem;
            color: #fff;
            cursor: pointer;
            font: inherit;
            font-weight: 700;
            text-decoration: none;
            transition: transform 160ms ease, box-shadow 160ms ease, filter 160ms ease;
        }

        .btn:hover {
            color: #fff;
            filter: brightness(0.95);
            transform: translateY(-1px);
        }

        .btn-info {
            background: #08a9c7;
            box-shadow: 0 0.35rem 0.8rem rgb(8 169 199 / 20%);
        }

        .btn-primary {
            background: #2563eb;
            box-shadow: 0 0.35rem 0.8rem rgb(37 99 235 / 20%);
        }

        .btn-danger {
            background: #e23b4f;
            box-shadow: 0 0.35rem 0.8rem rgb(226 59 79 / 20%);
        }

        .btn-secondary {
            background: #64748b;
        }

        .my-3 {
            margin-top: 1rem;
            margin-bottom: 1.5rem;
        }

        .d-inline {
            display: inline;
        }

        .table {
            width: 100%;
            margin: 0 0 1.5rem;
            overflow: hidden;
            border-collapse: separate;
            border-spacing: 0;
            border: 1px solid #dce5f0;
            border-radius: 0.85rem;
            background: #fff;
            box-shadow: 0 0.8rem 2rem rgb(35 61 91 / 8%);
        }

        .table th,
        .table td {
            padding: 1rem 1.1rem;
            text-align: left;
            border-bottom: 1px solid #e8eef5;
        }

        .table th {
            color: #23395d;
            font-size: 0.82rem;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        .table tbody tr:last-child td {
            border-bottom: 0;
        }

        .table tbody tr:hover {
            background: #f7fbff;
        }

        .table-primary {
            background: #dbeafe;
        }

        .table-light {
            background: #fff;
        }

        .table-dark {
            color: #172033;
        }

        .table-striped tbody tr:nth-child(even) {
            background: #f8fafc;
        }

        .pagination {
            display: flex;
            gap: 0.35rem;
            padding: 0;
            margin: 1.25rem 0 0;
            list-style: none;
        }

        .page-item .page-link {
            display: block;
            min-width: 2.5rem;
            padding: 0.65rem 0.8rem;
            border: 1px solid #d8e2ee;
            border-radius: 0.55rem;
            background: #fff;
            color: #2563eb;
            text-align: center;
            text-decoration: none;
        }

        .page-item.active .page-link {
            border-color: #2563eb;
            background: #2563eb;
            color: #fff;
        }

        .page-item.disabled .page-link {
            color: #94a3b8;
            cursor: not-allowed;
        }

        form {
            max-width: 650px;
            padding: 1.5rem;
            border: 1px solid #dce5f0;
            border-radius: 0.85rem;
            background: #fff;
            box-shadow: 0 0.8rem 2rem rgb(35 61 91 / 8%);
        }

        .mb-3 {
            margin-bottom: 1.25rem;
        }

        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            color: #334155;
            font-weight: 700;
        }

        .form-control {
            display: block;
            width: 100%;
            padding: 0.75rem 0.85rem;
            border: 1px solid #cbd5e1;
            border-radius: 0.55rem;
            color: #172033;
            font: inherit;
        }

        .form-control:focus {
            border-color: #2563eb;
            outline: 3px solid rgb(37 99 235 / 15%);
        }

        .is-invalid {
            border-color: #e23b4f;
        }

        .invalid-feedback,
        .alert {
            color: #b42335;
            font-size: 0.9rem;
        }

        .invalid-feedback {
            display: block;
            margin-top: 0.35rem;
        }

        .alert {
            margin-bottom: 1rem;
            padding: 0.9rem 1rem;
            border: 1px solid #fecdd3;
            border-radius: 0.6rem;
            background: #fff1f2;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        .dashboard-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            display: flex;
            flex-direction: column;
            gap: 0.35rem;
            padding: 1.25rem;
            border: 1px solid #dce5f0;
            border-radius: 0.85rem;
            background: #fff;
            box-shadow: 0 0.8rem 2rem rgb(35 61 91 / 8%);
        }

        .stat-card strong {
            color: #2563eb;
            font-size: 2rem;
        }

        .stat-card span {
            color: #64748b;
        }

        h2 {
            margin-top: 2rem;
        }

        @media (max-width: 700px) {
            .dashboard-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <main class="container py-4">
        @yield('content')
    </main>

    @stack('scripts')
</body>
</html>
