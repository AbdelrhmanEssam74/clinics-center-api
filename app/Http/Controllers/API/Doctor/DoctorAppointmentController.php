<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;
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
            ->with(['patient'])
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
        $doctorId = 1;
        // Validate the request
        $request->validate([
            'status' => 'required|string|in:confirmed,cancelled,completed'
        ]);
        // Find the appointment by ID and doctor ID
        $appointment = Appointment::where('doctor_id', $doctorId)
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
}
