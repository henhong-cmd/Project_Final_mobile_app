@extends('mylaout.layout1')

@section('title', 'Add Enrollment')

@section('content')
    <h1>Add Enrollment</h1>

    @if ($errors->any())
        <div class="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('enrollments.store') }}">
        @csrf
        <div class="mb-3">
            <label for="student_id" class="form-label">Student</label>
            <select class="form-control" id="student_id" name="student_id" required>
                <option value="">Select a student</option>
                @foreach ($students as $student)
                    <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->full_name }} ({{ $student->email }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="course_id" class="form-label">Course</label>
            <select class="form-control" id="course_id" name="course_id" required>
                <option value="">Select a course</option>
                @foreach ($courses as $course)
                    <option value="{{ $course->id }}" @selected(old('course_id') == $course->id)>{{ $course->code }} - {{ $course->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="enrolled_at" class="form-label">Enrollment date</label>
            <input class="form-control" id="enrolled_at" name="enrolled_at" type="date" value="{{ old('enrolled_at', now()->toDateString()) }}" required>
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status" required>
                @foreach (['active', 'completed', 'dropped'] as $status)
                    <option value="{{ $status }}" @selected(old('status', 'active') === $status)>{{ ucfirst($status) }}</option>
                @endforeach
            </select>
        </div>
        <button class="btn btn-primary">Save enrollment</button>
        <a href="{{ route('enrollments.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
