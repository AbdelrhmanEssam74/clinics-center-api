<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
use App\Models\Doctor;
use Carbon\Carbon;

class DoctorAppointmentController extends Controller
{
    // get all appointments for a doctor
    public function index(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role_id !== 2) {
            return response()->json(['message' => 'Forbidden: Not a doctor'], 403);
        }

        $doctorId = $user->id;
        $appointments = Appointment::where('doctor_id', $doctorId)
            ->with(
                [
                    'patient:id,user_id,medical_record_number,date_of_birth,gender,phone',
                    'patient.user:id,name,phone,image,created_at'
                ]
            )
            ->get();

        return response()->json([
            'appointments' => $appointments
        ], 200);
    }
    // get upcoming appointments for a doctor
    public function upcoming(Request $request)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role_id !== 2) {
            return response()->json(['message' => 'Forbidden: Not a doctor'], 403);
        }

        $doctorId = $user->id;
        $currentDateTime = Carbon::now();
        $upcomingAppointments = Appointment::where('doctor_id', $doctorId)
            ->where('appointment_date', '>', $currentDateTime)
            ->with(['patient'])
            ->get();

        return response()->json([
            'upcoming_appointments' => $upcomingAppointments
        ], 200);
    }
    // get specific appointment for a specific doctor
    public function show($id)
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if ($user->role_id !== 2) {
            return response()->json(['message' => 'Forbidden: Not a doctor'], 403);
        }
        $doctorId = $user->id;
        $appointment = Appointment::where('doctor_id', $doctorId)
            ->where('id', $id)
            ->with(['patient'])
            ->first();
        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        return response()->json([
            'appointment' => $appointment
        ], 200);
    }
    // update appointment status
    public function updateStatus(Request $request, $id)
    {
        $user = auth()->user();
        $doctorId = Doctor::select('id')->where('user_id', $user->id)->first();
        // Validate the request
        $request->validate([
            'status' => 'required|string|in:pending,confirmed,cancelled,completed'
        ]);
        // Find the appointment by ID and doctor ID
        $appointment = Appointment::where('doctor_id', $doctorId->id)
            ->where('id', $id)
            ->first();

        if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }

        $appointment->status = $request->input('status');
        $appointment->save();
        return response()->json([
            'message' => 'Appointment status updated successfully',
            'appointment' => $appointment
        ], 200);
    }
    public function getAppointmentPayment($appointment_id)
    {
        $user = auth()->user();
        $doctorId = Doctor::select('id')->where('user_id', $user->id)->first();
        $appointment = Appointment::select('payment_status' , 'payment_method')->where('doctor_id', $doctorId->id)
            ->where('id', $appointment_id)
            ->first();
                    if (!$appointment) {
            return response()->json(['message' => 'Appointment not found'], 404);
        }
        return response()->json([
            'appointment' => $appointment
        ], 200);
    }
}
