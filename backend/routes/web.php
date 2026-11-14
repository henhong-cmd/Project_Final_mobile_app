<?php

use App\Http\Controllers\CourseController;
use App\Http\Controllers\EnrollmentController;
use App\Http\Controllers\StudentController;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Student;
use Illuminate\Support\Facades\Route;

Route::get('/students', [StudentController::class, 'index'])
    ->name('students.index');
Route::get('/students/create', [StudentController::class, 'create'])
    ->name('students.create');
Route::post('/students', [StudentController::class, 'store'])
    ->name('students.store');
Route::get('/students/{student}/edit', [StudentController::class, 'edit'])
    ->name('students.edit');
Route::put('/students/{student}', [StudentController::class, 'update'])
    ->name('students.update');
Route::delete('/students/{student}', [StudentController::class, 'destroy'])
    ->name('students.destroy');

Route::resource('courses', CourseController::class)->only(['index', 'create', 'store']);
Route::resource('enrollments', EnrollmentController::class)->only(['index', 'create', 'store']);

Route::get('/dashboard', function () {
    return view('dashboard', [
        'studentCount' => Student::count(),
        'courseCount' => Course::count(),
        'enrollmentCount' => Enrollment::count(),
        'enrollments' => Enrollment::with(['student', 'course'])->latest()->take(10)->get(),
    ]);
})->name('dashboard');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/hello', function () {
    return '<h1>Hello Laravel</h1>';
});

Route::get('/hi', function () {
    return '<h1>Hi NUM</h1>';
});

Route::get('/sum/{a}/{b}', function ($a, $b) {
    $total = $a + $b;

    return "<h1>Total = $total</h1>";
});

Route::get('/cv', function () {
    return view('cv');

});

// :: access to static member
// -> access to non-static member
