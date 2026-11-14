<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CourseEnrollmentTest extends TestCase
{
    use RefreshDatabase;

    public function test_student_can_enroll_in_a_course_once(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $course = $this->postJson('/api/courses', [
            'name' => 'Dart Fundamentals',
            'description' => 'Learn the Dart language.',
            'duration' => '6 weeks',
            'price' => 99.50,
        ])->assertCreated()->assertJsonPath('name', 'Dart Fundamentals');

        $student = $this->postJson('/api/students', [
            'full_name' => 'Alex Student',
        ])->assertCreated()->assertJsonPath('full_name', 'Alex Student');

        $enrollmentData = [
            'course_id' => $course->json('id'),
            'student_id' => $student->json('id'),
        ];

        $this->postJson('/api/enrollments', $enrollmentData)
            ->assertCreated()
            ->assertJsonPath('course.id', $enrollmentData['course_id'])
            ->assertJsonPath('student.id', $enrollmentData['student_id']);

        $this->postJson('/api/enrollments', $enrollmentData)
            ->assertUnprocessable()
            ->assertJsonValidationErrors('student_id');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_guest_can_list_enrollments_after_a_student_enrolls(): void
    {
        $course = Course::create([
            'name' => 'Dart Fundamentals',
            'description' => 'Learn the Dart language.',
            'duration' => '6 weeks',
            'price' => 99.50,
        ]);
        $student = Student::create(['full_name' => 'Alex Student']);

        $this->postJson('/api/enrollments', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ])->assertCreated();

        $this->getJson('/api/enrollments')
            ->assertOk()
            ->assertJsonPath('0.course.id', $course->id)
            ->assertJsonPath('0.student.id', $student->id)
            ->assertJsonPath('0.student.full_name', 'Alex Student');

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_browser_request_to_api_enrollments_displays_a_table(): void
    {
        $course = Course::create([
            'name' => 'Dart Fundamentals',
            'description' => 'Learn the Dart language.',
            'duration' => '6 weeks',
            'price' => 99.50,
        ]);
        $student = Student::create(['full_name' => 'Alex Student']);

        $this->postJson('/api/enrollments', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ])->assertCreated();

        $this->get('/api/enrollments')
            ->assertOk()
            ->assertHeader('content-type', 'text/html; charset=UTF-8')
            ->assertSee('<table', false)
            ->assertSee('Alex Student')
            ->assertSee('Dart Fundamentals')
            ->assertSee('Enrolled at');
    }

    public function test_api_request_to_enrollments_still_receives_json(): void
    {
        $course = Course::create([
            'name' => 'Dart Fundamentals',
            'description' => 'Learn the Dart language.',
            'duration' => '6 weeks',
            'price' => 99.50,
        ]);
        $student = Student::create(['full_name' => 'Alex Student']);

        $this->postJson('/api/enrollments', [
            'course_id' => $course->id,
            'student_id' => $student->id,
        ])->assertCreated();

        $this->getJson('/api/enrollments')
            ->assertOk()
            ->assertJsonPath('0.student.full_name', 'Alex Student')
            ->assertJsonPath('0.course.name', 'Dart Fundamentals');
    }
}
