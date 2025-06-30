<?php

use App\Http\Controllers\API\Doctor\HomeController;
use Illuminate\Http\Request;
use App\Http\Controllers\AppointmentController;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// appointments
Route::apiResource('appointments', AppointmentController::class);

// Doctor API routes - Abdelrhman
Route::get('doctor/home', [HomeController::class, 'APITest'])
    ->name('doctor.home');
