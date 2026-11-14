@extends('mylaout.layout1')

@section('title', 'Student Enrollment Dashboard')

@section('content')
    <h1>Student Enrollment Dashboard</h1>

    <div class="dashboard-grid">
        <div class="stat-card"><strong>{{ $studentCount }}</strong><span>Students</span></div>
        <div class="stat-card"><strong>{{ $courseCount }}</strong><span>Courses</span></div>
        <div class="stat-card"><strong>{{ $enrollmentCount }}</strong><span>Enrollments</span></div>
    </div>

    <p>
        <a href="{{ route('students.index') }}" class="btn btn-primary">Manage students</a>
        <a href="{{ route('courses.index') }}" class="btn btn-info">Manage courses</a>
        <a href="{{ route('enrollments.index') }}" class="btn btn-info">Manage enrollments</a>
        <a href="/api/students" class="btn btn-secondary">Open API</a>
    </p>

    <h2>Latest enrollments</h2>
    <table class="table table-dark table-striped">
        <thead class="table-primary">
            <tr>
                <th>Student</th>
                <th>Course</th>
                <th>Enrolled at</th>
                <th>Status</th>
            </tr>
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
@endsection
