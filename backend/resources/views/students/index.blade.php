@extends('mylaout.layout1')

@section('title', 'បញ្ជីនិស្សិត')

@section('content')
    <h1>បញ្ជីនិស្សិត</h1>

    <div class="my-3">
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">ផ្ទាំងគ្រប់គ្រង</a>
        <a href="{{ route('students.create') }}" class="btn btn-info">បន្ថែមនិស្សិតថ្មី</a>
    </div>

    <table class="table table-hover table-dark table-striped">
        <thead class="table-primary">
            <tr>
                <th scope="col">លេខសម្គាល់</th>
                <th scope="col">ឈ្មោះ</th>
                <th scope="col">អ៊ីមែល</th>
                <th scope="col">ទូរស័ព្ទ</th>
                <th scope="col">សកម្មភាព</th>
            </tr>
        </thead>
        <tbody class="table-light">
            @forelse ($students as $student)
                <tr>
                    <td>{{ $student->id }}</td>
                    <td>{{ $student->full_name }}</td>
                    <td>{{ $student->email }}</td>
                    <td>{{ $student->phone ?? '—' }}</td>
                    <td>
                        <a href="{{ route('students.edit', $student) }}" class="btn btn-info">កែប្រែ</a>
                        <form method="POST" action="{{ route('students.destroy', $student) }}" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="button" class="btn btn-danger btn-delete">លុប</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">មិនមានទិន្នន័យនិស្សិតទេ។</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $students->links() }}
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function () {
                const form = this.closest('form');
                Swal.fire({
                    title: "តើពិតជាចង់លុបទិន្ន័យនេះមែនទេ?",
                    text: "ពេលលុបចេញ​ហើយ​​ គេមិនអាចស្ដារឡើងវិញបានទេ!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#3085d6",
                    confirmButtonText: "បញ្ជាក់ការលុប",
                    cancelButtonText: "បោះបង់"
                }).then(result => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    </script>
@endpush
