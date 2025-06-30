<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Patient\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('appointments/patient', AppointmentController::class);
