<?php

use App\Http\Controllers\API\Doctor\DoctorAppointmentController;
use App\Http\Controllers\API\Doctor\DoctorHomeController;
use App\Http\Controllers\API\Doctor\DoctorPatientController;
use App\Http\Controllers\API\Doctor\DoctorProfileController;
use App\Http\Controllers\API\Doctor\DoctorTimeSlotsController;
use App\Http\Controllers\API\patient\PatientAppointmentController;
use App\Http\Controllers\API\patient\PatientProfileController;
use App\Http\Controllers\API\Search\SearchController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\users\UserController;
use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PayPalController;



Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Patient API routes - Ahmed sayed
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('appointments/patient', PatientAppointmentController::class);
    Route::get('/appointments/mine', [PatientAppointmentController::class, 'mine']);

});

// add and edit and delete and show user to admin  =>ahmed abdelhalim
Route::apiResource('users', UserController::class);

//  auth register
Route::post('register', [AuthController::class, 'register']);
// auth login
Route::post('login', [AuthController::class, 'login']);
// auth profile
Route::get('profile', [AuthController::class, 'profile'])->middleware('auth:sanctum');
// auth logout
Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


// Doctor API routes - Abdelrhman
Route::get('doctor/profile', [DoctorHomeController::class, 'home'])
    ->name('doctor.profile')->middleware('auth:sanctum');
Route::put('doctor/profile/update', [DoctorProfileController::class, 'update'])
    ->name('doctor.profile')->middleware('auth:sanctum');
// Doctor appointments -> get all appointments for a doctor
Route::get('doctor/appointments', [DoctorAppointmentController::class, 'index'])
    ->name('doctor.appointments.index')->middleware('auth:sanctum');
// Doctor appointments -> get upcoming appointments for a doctor
Route::get('doctor/appointments/upcoming', [DoctorAppointmentController::class, 'upcoming'])
    ->name('doctor.appointments.upcoming')->middleware('auth:sanctum');
// Doctor appointments -> get specific appointment for a doctor
Route::get('doctor/appointments/{id}', [DoctorAppointmentController::class, 'show'])
    ->name('doctor.appointments.show');
// Doctor appointments -> update appointment status
Route::put('doctor/appointments/{id}/status', [DoctorAppointmentController::class, 'updateStatus'])
    ->name('doctor.appointments.updateStatus');
// Doctor Slots -> get all time slots for a doctor
Route::get('doctor/time-slots', [DoctorTimeSlotsController::class, 'index'])
    ->name('doctor.time-slots.index')->middleware('auth:sanctum');
// Doctor Slots -> add a new time slot for a doctor
Route::post('doctor/time-slots/store', [DoctorTimeSlotsController::class, 'store'])
    ->name('doctor.time-slots.store')->middleware('auth:sanctum');
    // Doctor Slots -> show slot for a doctor
Route::get('doctor/time-slots/show/{id}', [DoctorTimeSlotsController::class, 'show'])
    ->name('doctor.time-slots.show')->middleware('auth:sanctum');
    // Doctor Slots -> update  time slot for a doctor
Route::put('doctor/time-slots/update/{id}', [DoctorTimeSlotsController::class, 'update'])
    ->name('doctor.time-slots.update')->middleware('auth:sanctum');
// Doctor Slots -> delete a time slot for a doctor
Route::delete('doctor/time-slots/delete/{id}', [DoctorTimeSlotsController::class, 'destroy'])
    ->name('doctor.time-slots.destroy')->middleware('auth:sanctum');
// Doctor Patients -> get all patients who had appointments with the doctor
Route::get('doctor/patients', [DoctorPatientController::class, 'index'])
    ->name('doctor.patients.index')->middleware('auth:sanctum');
// Doctor Patients -> get specific patient by id
Route::get('doctor/patients/{id}', [DoctorPatientController::class, 'show'])
    ->name('doctor.patients.show')->middleware('auth:sanctum');


// search task - Mariam
// // Get all doctors
Route::get('/doctors', [SearchController::class, 'index'])
    ->name('show.all.doctors');

    // get specific doctor by id
    Route::get('/doctors/{id}', [SearchController::class, 'getById'])
    ->name('show.doctor.id');

// search
Route::get('/doctors/search/{searchTerm}', [SearchController::class, 'search']) ;


    //get doctor's available time slots
Route::get('/doctors/{id}/time_slots', [SearchController::class, 'getAvailTimeSlots'])
    ->name('show.doctor.time-slots');


// patient profile
Route::get('/patient/profile', [PatientProfileController::class, 'show'])
    ->name('patient.profile.show')
    ->middleware('auth:sanctum');


// update patient profile
Route::put('/patient/profile/update', [PatientProfileController::class, 'update'])
    ->name('patient.profile.update')
    ->middleware('auth:sanctum');

// get patient id  => Ahmed  abdelhalim
Route::get('/patient/id', function () {
$user = Auth::user();

    $patient = Patient::where('user_id', $user->id)->first();

    if (!$patient) {
        return response()->json(['error' => 'Patient not found'], 404);
    }

    return response()->json(['patient_id' => $patient->id]);
})->middleware('auth:sanctum');


    //  doctor profile
    Route::middleware('auth:sanctum')->group(function () {
        // doctor profile
        Route::get('doctor/profile', [DoctorProfileController::class, 'show'])
            ->name('doctor.profile.show');

    // update doctor profile
        Route::put('doctor/profile/update', [DoctorProfileController::class, 'update'])
            ->name('doctor.profile.update');
    });

    // PayPal routes
Route::post('/paypal/create', [PayPalController::class, 'createTransaction']);
Route::get('/paypal/success', [PayPalController::class, 'captureTransaction'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancelTransaction'])->name('paypal.cancel');
