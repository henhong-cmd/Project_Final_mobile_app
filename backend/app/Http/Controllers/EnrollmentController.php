<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
    public function index(): View
    {
        return view('enrollments.index', [
            'enrollments' => Enrollment::with(['student', 'course'])->latest()->paginate(10),
        ]);
    }

    public function create(): View
    {
        return view('enrollments.create', [
            'students' => Student::orderBy('full_name')->get(),
            'courses' => Course::orderBy('code')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'student_id' => ['required', 'exists:tbl_student,id'],
            'course_id' => ['required', 'exists:courses,id'],
            'enrolled_at' => ['required', 'date'],
            'status' => ['required', 'string', 'in:active,completed,dropped'],
        ]);

        Enrollment::create($validated);

        return redirect()->route('enrollments.index');
    }
}
