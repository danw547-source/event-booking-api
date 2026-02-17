<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EventController;
use App\Http\Controllers\AttendeeController;
use App\Http\Controllers\BookingController;

// API resource route registration for events, attendees, and bookings.

// Events
Route::apiResource('events', EventController::class);

// Attendees
Route::apiResource('attendees', AttendeeController::class);

// Bookings
Route::apiResource('bookings', BookingController::class);