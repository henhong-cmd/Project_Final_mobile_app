<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StudentApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Student::with('courses')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'max:255', 'unique:tbl_student,email'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $student = Student::create($validated);

        return response()->json($student, 201);
    }

    public function show(Student $student): JsonResponse
    {
        return response()->json($student->load('courses'));
    }

    public function update(Request $request, Student $student): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:tbl_student,email,'.$student->id],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $student->update($validated);

        return response()->json($student->fresh());
    }

    public function destroy(Student $student): JsonResponse
    {
        $student->delete();

        return response()->json(null, 204);
    }
}
