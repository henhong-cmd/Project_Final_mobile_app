@extends('mylaout.layout1')

@section('title', 'Add Course')

@section('content')
    <h1>Add Course</h1>

    @if ($errors->any())
        <div class="alert"><ul>@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
    @endif

    <form method="POST" action="{{ route('courses.store') }}">
        @csrf
        <div class="mb-3">
            <label for="code" class="form-label">Course code</label>
            <input class="form-control" id="code" name="code" value="{{ old('code') }}" required>
        </div>
        <div class="mb-3">
            <label for="name" class="form-label">Course name</label>
            <input class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="credits" class="form-label">Credits</label>
            <input class="form-control" id="credits" name="credits" type="number" min="1" max="10" value="{{ old('credits', 3) }}" required>
        </div>
        <button class="btn btn-primary">Save course</button>
        <a href="{{ route('courses.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
@endsection
