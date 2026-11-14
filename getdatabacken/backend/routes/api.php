<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\StoreController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);
Route::get('/courses', [StoreController::class, 'courses']);
Route::get('/students', [StoreController::class, 'students']);
Route::post('/students', [StoreController::class, 'createStudent']);
Route::post('/enrollments', [StoreController::class, 'createEnrollment']);
Route::get('/enrollments', [StoreController::class, 'enrollments']);

Route::middleware('auth:sanctum')->group(function (): void {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/courses', [StoreController::class, 'createCourse']);
    Route::get('/products', [StoreController::class, 'products']);
    Route::post('/products', [StoreController::class, 'createProduct']);
    Route::get('/customers', [StoreController::class, 'customers']);
    Route::post('/customers', [StoreController::class, 'createCustomer']);
    Route::get('/purchases', [StoreController::class, 'purchases']);
    Route::post('/purchases', [StoreController::class, 'createPurchase']);
});
