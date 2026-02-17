<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
	return response()->json([
		'name' => 'Event Booking API',
		'status' => 'ok',
	]);
});
