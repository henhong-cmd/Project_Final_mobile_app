@extends('mylaout.layout1')

@section('title', 'បង្កើតទិន្ន័យនិស្សិត')

@section('content')
    <h1>បង្កើតទិន្ន័យនិស្សិត</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('students.store') }}">
        @csrf

        <div class="mb-3">
            <label for="full_name" class="form-label">ឈ្មោះពេញ</label>
            <input
                type="text" 
                name="full_name"
                id="full_name"
                value="{{ old('full_name') }}"
                class="form-control @error('full_name') is-invalid @enderror"
                required
                autofocus
            >
            @error('full_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">អ៊ីមែល</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}"
                class="form-control @error('email') is-invalid @enderror" required>
            @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="phone" class="form-label">ទូរស័ព្ទ</label>
            <input type="text" name="phone" id="phone" value="{{ old('phone') }}"
                class="form-control @error('phone') is-invalid @enderror">
            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">បន្ថែម</button>
        <a href="{{ route('students.index') }}" class="btn btn-secondary">បោះបង់</a>
    </form>
@endsection
