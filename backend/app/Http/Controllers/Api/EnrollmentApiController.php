<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class EnrollmentApiController extends Controller
{
    private const DUPLICATE_ENROLLMENT_MESSAGE = 'This student is already enrolled in this course.';

    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Enrollment::with(['student', 'course'])->get());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:tbl_student,id',
                Rule::unique('enrollments', 'student_id')
                    ->where('course_id', $request->input('course_id')),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'enrolled_at' => ['required', 'date'],
            'status' => ['sometimes', 'string', 'in:active,completed,dropped'],
        ], [
            'student_id.unique' => self::DUPLICATE_ENROLLMENT_MESSAGE,
        ]);

        try {
            $enrollment = Enrollment::create($validated);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'student_id' => self::DUPLICATE_ENROLLMENT_MESSAGE,
            ]);
        }

        return response()->json($enrollment->load(['student', 'course']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Enrollment $enrollment): JsonResponse
    {
        return response()->json($enrollment->load(['student', 'course']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Enrollment $enrollment): JsonResponse
    {
        $validated = $request->validate([
            'student_id' => [
                'required',
                'exists:tbl_student,id',
                Rule::unique('enrollments', 'student_id')
                    ->where('course_id', $request->input('course_id'))
                    ->ignore($enrollment->id),
            ],
            'course_id' => ['required', 'exists:courses,id'],
            'enrolled_at' => ['required', 'date'],
            'status' => ['required', 'string', 'in:active,completed,dropped'],
        ], [
            'student_id.unique' => self::DUPLICATE_ENROLLMENT_MESSAGE,
        ]);

        try {
            $enrollment->update($validated);
        } catch (UniqueConstraintViolationException) {
            throw ValidationException::withMessages([
                'student_id' => self::DUPLICATE_ENROLLMENT_MESSAGE,
            ]);
        }

        return response()->json($enrollment->fresh()->load(['student', 'course']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Enrollment $enrollment): JsonResponse
    {
        $enrollment->delete();

        return response()->json(null, 204);
    }
}
