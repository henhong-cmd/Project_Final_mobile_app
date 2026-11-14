<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StudentEnrollmentApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_valid_student_payload_creates_a_student_and_returns_201(): void
    {
        $response = $this->postJson('/api/students', [
            'full_name' => 'Dara Sok',
            'email' => 'dara@example.com',
            'phone' => '012345678',
        ]);

        $response->assertCreated()
            ->assertJsonPath('full_name', 'Dara Sok')
            ->assertJsonPath('email', 'dara@example.com');

        $this->assertDatabaseHas('tbl_student', [
            'email' => 'dara@example.com',
        ]);
    }

    public function test_student_can_be_created_without_email(): void
    {
        $response = $this->postJson('/api/students', [
            'full_name' => 'Dara Sok',
        ]);

        $response->assertCreated()
            ->assertJsonPath('full_name', 'Dara Sok')
            ->assertJsonPath('email', null);

        $this->assertDatabaseHas('tbl_student', [
            'full_name' => 'Dara Sok',
            'email' => null,
        ]);
    }

    public function test_student_creation_still_requires_a_full_name(): void
    {
        $this->postJson('/api/students', [])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['full_name']);

        $this->assertDatabaseCount('tbl_student', 0);
    }

    public function test_enrollment_returns_related_student_and_course(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $response = $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => '2026-11-08',
            'status' => 'active',
        ]);

        $response->assertCreated()
            ->assertJsonPath('student.id', $student->id)
            ->assertJsonPath('course.id', $course->id);

        $this->assertDatabaseHas('enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'status' => 'active',
        ]);
    }

    public function test_student_cannot_enroll_in_the_same_course_twice(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();
        $payload = [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => '2026-11-08',
            'status' => 'active',
        ];

        $this->postJson('/api/enrollments', $payload)->assertCreated();

        $this->postJson('/api/enrollments', $payload)
            ->assertUnprocessable()
            ->assertJsonValidationErrors([
                'student_id' => 'This student is already enrolled in this course.',
            ]);

        $this->assertDatabaseCount('enrollments', 1);
    }

    public function test_student_can_enroll_in_a_different_course(): void
    {
        $student = Student::factory()->create();
        $firstCourse = Course::factory()->create();
        $secondCourse = Course::factory()->create();

        foreach ([$firstCourse, $secondCourse] as $course) {
            $this->postJson('/api/enrollments', [
                'student_id' => $student->id,
                'course_id' => $course->id,
                'enrolled_at' => '2026-11-08',
                'status' => 'active',
            ])->assertCreated();
        }

        $this->assertDatabaseCount('enrollments', 2);
    }

    public function test_enrollment_cannot_be_updated_to_an_existing_student_course_pair(): void
    {
        $student = Student::factory()->create();
        $firstCourse = Course::factory()->create();
        $secondCourse = Course::factory()->create();

        $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $firstCourse->id,
            'enrolled_at' => '2026-11-08',
            'status' => 'active',
        ])->assertCreated();

        $secondEnrollment = $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $secondCourse->id,
            'enrolled_at' => '2026-11-08',
            'status' => 'active',
        ])->assertCreated();

        $this->putJson('/api/enrollments/'.$secondEnrollment->json('id'), [
            'student_id' => $student->id,
            'course_id' => $firstCourse->id,
            'enrolled_at' => '2026-11-08',
            'status' => 'active',
        ])->assertUnprocessable()
            ->assertJsonValidationErrors([
                'student_id' => 'This student is already enrolled in this course.',
            ]);

        $this->assertDatabaseHas('enrollments', [
            'id' => $secondEnrollment->json('id'),
            'course_id' => $secondCourse->id,
        ]);
    }

    public function test_enrollment_can_be_updated_without_changing_its_student_course_pair(): void
    {
        $student = Student::factory()->create();
        $course = Course::factory()->create();

        $enrollment = $this->postJson('/api/enrollments', [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => '2026-11-08',
            'status' => 'active',
        ])->assertCreated();

        $this->putJson('/api/enrollments/'.$enrollment->json('id'), [
            'student_id' => $student->id,
            'course_id' => $course->id,
            'enrolled_at' => '2026-11-09',
            'status' => 'completed',
        ])->assertOk()
            ->assertJsonPath('status', 'completed');
    }
}
