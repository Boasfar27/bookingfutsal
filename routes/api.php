<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\FieldController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PaymentController;

// Public routes
Route::get('/fields', [FieldController::class, 'index']);
Route::get('/fields/{field}', [FieldController::class, 'show']);
Route::get('/fields/{field}/availability', [FieldController::class, 'availability']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User profile
    Route::get('/user', function (Request $request) {
        return $request->user()->load('roles');
    });

    // Booking routes
    Route::apiResource('bookings', BookingController::class);
    Route::get('/my-bookings', [BookingController::class, 'myBookings']);
    Route::post('/bookings/{booking}/cancel', [BookingController::class, 'cancel']);

    // Payment routes
    Route::apiResource('payments', PaymentController::class);
    Route::post('/payments/{payment}/upload-proof', [PaymentController::class, 'uploadProof']);
    Route::get('/bookings/{booking}/payment', [PaymentController::class, 'getByBooking']);
});
