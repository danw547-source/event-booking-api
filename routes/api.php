<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\AttendeeController;

// Define API resource routes
Route::apiResource('events', EventController::class);
Route::apiResource('bookings', BookingController::class);
Route::apiResource('attendees', AttendeeController::class);

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where all API routes for the Event Booking API are defined.
| These routes are stateless (no sessions) and return JSON responses.
|
| The "api" middleware is applied automatically in RouteServiceProvider.
|
*/

// ✅ Basic test route (optional, good for verifying setup)
Route::get('/test', function () {
    return response()->json(['message' => 'API works!']);
});

// ✅ Event management routes
Route::apiResource('events', EventController::class);

// ✅ Attendee management routes
Route::apiResource('attendees', AttendeeController::class);

// ✅ Booking route (custom endpoint)
Route::post('bookings', [BookingController::class, 'store']);
