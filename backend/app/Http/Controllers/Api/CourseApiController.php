<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CourseApiController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(Course::with('students')->get());
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:courses,code'],
            'name' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'between:1,10'],
        ]);

        return response()->json(Course::create($validated), 201);
    }

    public function show(Course $course): JsonResponse
    {
        return response()->json($course->load('students'));
    }

    public function update(Request $request, Course $course): JsonResponse
    {
        $validated = $request->validate([
            'code' => ['required', 'string', 'max:30', 'unique:courses,code,'.$course->id],
            'name' => ['required', 'string', 'max:255'],
            'credits' => ['required', 'integer', 'between:1,10'],
        ]);

        $course->update($validated);

        return response()->json($course->fresh());
    }

    public function destroy(Course $course): JsonResponse
    {
        $course->delete();

        return response()->json(null, 204);
    }
}
