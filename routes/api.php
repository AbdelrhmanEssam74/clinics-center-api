<?php

use App\Http\Controllers\API\Doctor\DoctorAppointmentController;
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
Route::get('doctor/profile/{id}', [HomeController::class, 'home'])
    ->name('doctor.profile');
// Doctor appointments -> get all appointments for a doctor
Route::get('doctor/appointments', [DoctorAppointmentController::class, 'index'])
    ->name('doctor.appointments.index');
