<?php

use App\Http\Controllers\API\Doctor\DoctorAppointmentController;
use App\Http\Controllers\API\Doctor\DoctorHomeController;
use App\Http\Controllers\API\Doctor\DoctorPatientController;
use App\Http\Controllers\API\Doctor\DoctorProfileController;
use App\Http\Controllers\API\Doctor\DoctorTimeSlotsController;
use App\Http\Controllers\API\patient\MedicalReportController;
use App\Http\Controllers\API\patient\PatientAppointmentController;
use App\Http\Controllers\API\patient\PatientProfileController;
use App\Http\Controllers\API\Rating\Reviews_Ratings_Doctors;
use App\Http\Controllers\API\Search\SearchController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\AppointmentController;
use App\Http\Controllers\admin\DoctorController;
use App\Http\Controllers\admin\PatientController;
use App\Http\Controllers\admin\SlotController;
use App\Http\Controllers\admin\AdminController;

use Illuminate\Http\Request;
use App\Models\Patient;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\ContactController;



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
Route::put('doctor/appointments/{id}/status', [DoctorAppointmentController::class, 'updateStatus'])->middleware('auth:sanctum');
// Doctor appointments -> get payment status
Route::get('doctor/appointments/{id}/payment/status', [DoctorAppointmentController::class, 'getAppointmentPayment'])
    ->name('doctor.appointments.updateStatus')->middleware('auth:sanctum');
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



// Reviews & Ratings - Abdelrhman
Route::middleware('auth:sanctum')->group(function () {
    Route::post('review-rating/add', [Reviews_Ratings_Doctors::class, 'create'])->name('post.review');
    Route::put('review-rating/update/{review_id}', [Reviews_Ratings_Doctors::class, 'update'])->name('update.review');
    Route::delete('review-rating/delete/{review_id}', [Reviews_Ratings_Doctors::class, 'delete'])->name('delete.review');
    Route::get('review-rating/show/{review_id}', [Reviews_Ratings_Doctors::class, 'show'])->name('show.review');
    Route::get('review-rating/doctorReviews/{review_id}', [Reviews_Ratings_Doctors::class, 'doctorReviews'])->name('doctorReviews.review');
    Route::get('review-rating/myReviews', [Reviews_Ratings_Doctors::class, 'myReviews'])->name('myReviews.review');
});
// search task - Mariam
// // Get all doctors
Route::get('/doctors', [SearchController::class, 'index'])
    ->name('show.all.doctors');

// get specific doctor by id
Route::get('/doctors/{id}', [SearchController::class, 'getById'])
    ->name('show.doctor.id');

// search
Route::get('/doctors/search/{searchTerm}', [SearchController::class, 'search']);


//get doctor's available time slots
Route::get('/doctors/{id}/time_slots', [SearchController::class, 'getAvailTimeSlots'])
    ->name('show.doctor.time-slots');


// patient profile
Route::get('/patient/profile', [PatientProfileController::class, 'show'])
    ->name('patient.profile.show')
    ->middleware('auth:sanctum');

// update patient profile
Route::put('/patient/profile/update', [PatientProfileController::class, 'update'])
    ->middleware('auth:sanctum');
    // update patient image
Route::post('/patient/profile/update_image', [PatientProfileController::class, 'updateImage'])
    ->middleware('auth:sanctum');
    // reports
Route::middleware('auth:sanctum')->group(function () {
    // display
    Route::get('/patient/medical_reports', [MedicalReportController::class, 'index']);
    // add
    Route::post('/patient/medical_reports', [MedicalReportController::class, 'store']);
    // delete
    Route::delete('/patient/medical_reports/{report}', [MedicalReportController::class, 'destroy']);  
    // doctor access report for patients
     Route::get('patients/{patient}/reports', [MedicalReportController::class, "getPatientReports"]);

});
//

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
    // update doctor image
    Route::post('doctor/image/update', [DoctorProfileController::class, 'updateImage'])->name('doctor.image.update');
});


// PayPal routes
Route::post('/paypal/create', [PayPalController::class, 'createTransaction'])->middleware('auth:sanctum');
Route::get('/paypal/success', [PayPalController::class, 'captureTransaction'])->name('paypal.success');
Route::get('/paypal/cancel', [PayPalController::class, 'cancelTransaction'])->name('paypal.cancel');


//admin routes
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    Route::apiResource('admin/patients', PatientController::class);

    Route::get('admin/doctors/dropdown', [SlotController::class, 'dropdown']);

    Route::apiResource('admin/doctors', DoctorController::class);

    Route::apiResource('admin/appointments', AppointmentController::class);
    Route::apiResource('admin/slots', SlotController::class);
    Route::apiResource('admin/users', UserController::class);
    Route::get('admin/dashboard-data', [AdminController::class, 'dashboardData']);
    Route::put('admin/profile/update', [AdminController::class, 'updateAdminProfile']);
    Route::post('admin/profile/update_image', [AdminController::class, 'updateImage']);

    Route::get('admin/profile', [AdminController::class, 'show']);
});


// Contact Us routes => Ahmed abdelhalim

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/contacts', [ContactController::class, 'index']);
    Route::post('/contacts', [ContactController::class, 'store']);
});
?>
