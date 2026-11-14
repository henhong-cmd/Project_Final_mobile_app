<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Contracts\View\View;

class EnrollmentPageController extends Controller
{
    public function __invoke(): View
    {
        return view('enrollments.index', [
            'enrollments' => Enrollment::with(['course', 'student'])
                ->latest()
                ->paginate(15),
        ]);
    }
}
