<?php

use App\Http\Controllers\EnrollmentPageController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/enrollments', EnrollmentPageController::class)->name('enrollments.index');
