@extends('mylaout.layout1')

@section('title', 'កែប្រែព័ត៌មាននិស្សិត')

@section('content')
        <h1>កែប្រែព័ត៌មាននិស្សិត</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">អ៊ីមែល</label>
                <input type="email" name="email" id="email" value="{{ old('email', $student->email) }}"
                    class="form-control @error('email') is-invalid @enderror" required>
                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>

            <div class="mb-3">
                <label for="phone" class="form-label">ទូរស័ព្ទ</label>
                <input type="text" name="phone" id="phone" value="{{ old('phone', $student->phone) }}"
                    class="form-control @error('phone') is-invalid @enderror">
                @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
        @endif

        <form method="POST" action="{{ route('students.update', $student) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="full_name" class="form-label">ឈ្មោះពេញ</label>
                <input
                    type="text"
                    name="full_name"
                    id="full_name"
                    value="{{ old('full_name', $student->full_name) }}"
                    class="form-control @error('full_name') is-invalid @enderror"
                    required
                    autofocus
                >
                @error('full_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">ធ្វើបច្ចុប្បន្នភាព</button>
            <a href="{{ route('students.index') }}" class="btn btn-secondary">បោះបង់</a>
        </form>
@endsection
