<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Enrollments</title>
    <style>
        :root {
            font-family: system-ui, sans-serif;
            color: #1f2937;
            background: #f3f4f6;
        }

        body {
            margin: 0;
            padding: 2rem 1rem;
        }

        main {
            max-width: 1000px;
            margin: 0 auto;
        }

        h1 {
            margin: 0 0 1.25rem;
        }

        .table-wrap {
            overflow-x: auto;
            border: 1px solid #d1d5db;
            border-radius: 0.75rem;
            background: #fff;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
        }

        th, td {
            padding: 0.9rem 1rem;
            border-bottom: 1px solid #e5e7eb;
        }

        th {
            background: #f9fafb;
        }

        tbody tr:last-child td {
            border-bottom: 0;
        }

        .empty {
            padding: 1.5rem;
            color: #6b7280;
            text-align: center;
        }

        .pagination {
            margin-top: 1rem;
        }
    </style>
</head>
<body>
    <main>
        <h1>Student Enrollments</h1>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th scope="col">Student</th>
                        <th scope="col">Course</th>
                        <th scope="col">Enrolled at</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($enrollments as $enrollment)
                        <tr>
                            <td>{{ $enrollment->student->full_name }}</td>
                            <td>{{ $enrollment->course->name }}</td>
                            <td>{{ $enrollment->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td class="empty" colspan="3">No student enrollments yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination">
            {{ $enrollments->links() }}
        </div>
    </main>
</body>
</html>
