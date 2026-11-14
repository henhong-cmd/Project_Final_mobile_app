<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Customer;
use App\Models\Enrollment;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Student;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    public function courses(): JsonResponse
    {
        return response()->json(Course::query()->latest()->get());
    }

    public function createCourse(Request $request): JsonResponse
    {
        $course = Course::create($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['required', 'string'],
            'duration' => ['required', 'string', 'max:80'],
            'price' => ['required', 'numeric', 'min:0'],
        ]));

        return response()->json($course, 201);
    }

    public function students(): JsonResponse
    {
        return response()->json(Student::query()->latest()->get());
    }

    public function createStudent(Request $request): JsonResponse
    {
        $student = Student::create($request->validate([
            'full_name' => ['required', 'string', 'max:120'],
        ]));

        return response()->json($student, 201);
    }

    public function enrollments(Request $request): JsonResponse|View
    {
        $enrollments = Enrollment::with(['course', 'student'])->latest();

        if ($request->expectsJson()) {
            return response()->json($enrollments->get());
        }

        return view('enrollments.index', [
            'enrollments' => $enrollments->paginate(15),
        ]);
    }

    public function createEnrollment(Request $request): JsonResponse
    {
        $data = $request->validate([
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'student_id' => [
                'required',
                'integer',
                'exists:students,id',
                Rule::unique('enrollments', 'student_id')
                    ->where(fn ($query) => $query->where('course_id', $request->input('course_id'))),
            ],
        ]);

        $enrollment = Enrollment::create($data);

        return response()->json($enrollment->load(['course', 'student']), 201);
    }

    public function products(): JsonResponse
    {
        return response()->json(Product::query()->latest()->get());
    }

    public function createProduct(Request $request): JsonResponse
    {
        $product = Product::create($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'sku' => ['required', 'string', 'max:40', 'unique:products,sku'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
        ]));

        return response()->json($product, 201);
    }

    public function customers(): JsonResponse
    {
        return response()->json(Customer::query()->latest()->get());
    }

    public function createCustomer(Request $request): JsonResponse
    {
        $customer = Customer::create($request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:150', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
        ]));

        return response()->json($customer, 201);
    }

    public function purchases(): JsonResponse
    {
        return response()->json(
            Purchase::with(['product', 'customer'])->latest()->get(),
        );
    }

    public function createPurchase(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'customer_id' => ['required', 'integer', 'exists:customers,id'],
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $purchase = DB::transaction(function () use ($data): Purchase {
            $product = Product::query()->lockForUpdate()->findOrFail($data['product_id']);
            if ($product->stock < $data['quantity']) {
                abort(422, 'Not enough product stock.');
            }

            $product->decrement('stock', $data['quantity']);

            return Purchase::create([
                ...$data,
                'total_price' => $product->price * $data['quantity'],
                'purchased_at' => now(),
            ]);
        });

        return response()->json($purchase->load(['product', 'customer']), 201);
    }
}
