@extends('mylaout.layout1')

@section('title', 'Enrollments')

@section('content')
    <h1>Enrollments</h1>
    <p>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
        <a href="{{ route('enrollments.create') }}" class="btn btn-primary">Add enrollment</a>
        <a href="{{ route('courses.index') }}" class="btn btn-info">Courses</a>
    </p>

    <table class="table table-dark table-striped">
        <thead class="table-primary">
            <tr><th>Student</th><th>Course</th><th>Enrolled at</th><th>Status</th></tr>
        </thead>
        <tbody class="table-light">
            @forelse ($enrollments as $enrollment)
                <tr>
                    <td>{{ $enrollment->student->full_name }}</td>
                    <td>{{ $enrollment->course->code }} - {{ $enrollment->course->name }}</td>
                    <td>{{ $enrollment->enrolled_at->format('Y-m-d') }}</td>
                    <td>{{ ucfirst($enrollment->status) }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No enrollments yet.</td></tr>
            @endforelse
        </tbody>
    </table>

    {{ $enrollments->links() }}
@endsection
