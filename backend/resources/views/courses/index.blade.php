@extends('mylaout.layout1')

@section('title', 'Courses')

@section('content')
    <h1>Courses</h1>
    <p>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">Dashboard</a>
        <a href="{{ route('courses.create') }}" class="btn btn-primary">Add course</a>
        <a href="{{ route('enrollments.index') }}" class="btn btn-info">Enrollments</a>
    </p>

    <table class="table table-dark table-striped">
        <thead class="table-primary">
            <tr><th>Code</th><th>Name</th><th>Credits</th><th>Students</th></tr>
        </thead>
        <tbody class="table-light">
            @forelse ($courses as $course)
                <tr>
                    <td>{{ $course->code }}</td>
                    <td>{{ $course->name }}</td>
                    <td>{{ $course->credits }}</td>
                    <td>{{ $course->students_count }}</td>
                </tr>
            @empty
                <tr><td colspan="4">No courses yet.</td></tr>
            @endforelse
        </tbody>
    </table>
@endsection
