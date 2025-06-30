<?php

use App\Http\Controllers\API\Doctor\DoctorAppointmentController;
use App\Http\Controllers\API\Doctor\DoctorPatientController;
use App\Http\Controllers\API\Doctor\DoctorTimeSlotsController;
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
// Doctor appointments -> get upcoming appointments for a doctor
Route::get('doctor/appointments/upcoming', [DoctorAppointmentController::class, 'upcoming'])
    ->name('doctor.appointments.upcoming');
// Doctor appointments -> get specific appointment for a doctor
Route::get('doctor/appointments/{id}', [DoctorAppointmentController::class, 'show'])
    ->name('doctor.appointments.show');
// Doctor appointments -> update appointment status
Route::put('doctor/appointments/{id}/status', [DoctorAppointmentController::class, 'updateStatus'])
    ->name('doctor.appointments.updateStatus');
// Doctor Slots -> get all time slots for a doctor
Route::get('doctor/time-slots', [DoctorTimeSlotsController::class, 'index'])
    ->name('doctor.time-slots.index');
// Doctor Slots -> add a new time slot for a doctor
Route::post('doctor/time-slots/store', [DoctorTimeSlotsController::class, 'store'])
    ->name('doctor.time-slots.store');
// Doctor Slots -> delete a time slot for a doctor
Route::delete('doctor/time-slots/{id}', [DoctorTimeSlotsController::class, 'destroy'])
    ->name('doctor.time-slots.destroy');
// Doctor Patients -> get all patients who had appointments with the doctor
Route::get('doctor/patients', [DoctorPatientController::class, 'index'])
    ->name('doctor.patients.index');
// Doctor Patients -> get specific patient by id
Route::get('doctor/patients/{id}', [DoctorPatientController::class, 'show'])
    ->name('doctor.patients.show');
