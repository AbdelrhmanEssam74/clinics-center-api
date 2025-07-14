<?php

namespace App\Http\Controllers\API\patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PatientAppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::with('doctor.user', 'patient')->get();
        return AppointmentResource::collection($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAppointmentRequest $request)
    {
        // Todo check if the authenticated patient is allowed to create an appointment
        // Todo check if the doctor is available at the requested time
        // Todo check if the slot is available and not booked by another patient
        $appointment = Appointment::create($request->validated());
        return response()->json([
            'message' => "Appointment Created Successfully",
            'appointment' => new AppointmentResource($appointment)
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    // Todo add check for the appointment authenticated patient => Ahmed abdelhalim
    public function mine(Request $request)
{
    $patient = $request->user()->patient;

    if (!$patient) {
        return response()->json(['message' => 'Not a patient'], 403);
    }

    $appointments = Appointment::with('doctor.user', 'patient')
        ->where('patient_id', $patient->id)
        ->get();

    return AppointmentResource::collection($appointments);
}
    public function show(string $id)
    {
        // Todo add check for the appointment belongs to the authenticated patient
        $appointment = Appointment::with('doctor.user', 'patient')->find($id);

        if (!$appointment) {
            return response()->json(['message' => "This appointment was not found."], 404);
        }
        return response()->json([
            'appointment' => new AppointmentResource($appointment)
        ], 200);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, string $id)
    {
        // Todo add check for the appointment belongs to the authenticated patient
        // Todo check if the appointment is not in the past
        // Todo check if the appointment is not already confirmed or completed
        // add fake patient id to test
        // patient_id = 1;
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->validated());
        return response()->json([
            'message' => "Appointment Updated Successfully",
            'appointment' => new AppointmentResource($appointment)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Todo add check for the appointment belongs to the authenticated patient
        // Todo check if the appointment is not in the past
        // Todo check if the appointment is not already confirmed or completed
        // add fake patient id to test
        // patient_id = 1;
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
        return response()->json([
            'message' => "Appointment Deleted Successfully"
        ], 200);
    }
}
