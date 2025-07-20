<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Mail\AppointmentMail;
use Illuminate\Support\Facades\Mail;
class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with(['doctor.user', 'patient.user']);

        // فلتر الحالة
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // فلتر بالدكتور عن طريق اسم المستخدم
        if ($request->has('doctor_name') && $request->doctor_name != '') {
            $doctorName = $request->doctor_name;
            $query->whereHas('doctor.user', function($q) use ($doctorName) {
                $q->where('name', 'LIKE', "%$doctorName%");
            });
        }

        // فلتر بالمريض عن طريق اسم المستخدم
        if ($request->has('patient_name') && $request->patient_name != '') {
            $patientName = $request->patient_name;
            $query->whereHas('patient.user', function($q) use ($patientName) {
                $q->where('name', 'LIKE', "%$patientName%");
            });
        }

        // فلتر حسب doctor_id
        if ($request->has('doctor_id') && $request->doctor_id != '') {
            $query->where('doctor_id', $request->doctor_id);
        }

        // فلتر حسب patient_id
        if ($request->has('patient_id') && $request->patient_id != '') {
            $query->where('patient_id', $request->patient_id);
        }

        return $query->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'appointment_date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'in:pending,confirmed,cancelled',
            'notes' => 'nullable|string',
        ]);

        return Appointment::create($data);
    }

    public function show($id)
    {
        return Appointment::with(['doctor.user', 'patient.user'])->findOrFail($id);
    }
public function updateStatusByAdmin(Request $request, $id)
{
    $request->validate([
        'status' => 'required|string|in:pending,confirmed,cancelled,completed'
    ]);

    $appointment = Appointment::with(['doctor.user', 'patient.user'])->find($id);

    if (!$appointment) {
        return response()->json(['message' => 'Appointment not found'], 404);
    }

    $appointment->status = $request->input('status');
    $appointment->save();

    $patient = $appointment->patient;
    $patientUser = $patient?->user;
    $patientEmail = $patientUser?->email;

    $doctor = $appointment->doctor;
    
    if (in_array($request->status, ['confirmed', 'cancelled']) && $patientEmail) {
        Mail::to($patientEmail)->send(new AppointmentMail($patient, $appointment, $doctor, $request->status));
    }

    return response()->json([
        'message' => 'Appointment status updated by admin successfully',
        'appointment' => $appointment
    ]);
}
    public function update(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        $data = $request->validate([
            'doctor_id' => 'sometimes|exists:doctors,id',
            'patient_id' => 'sometimes|exists:patients,id',
            'appointment_date' => 'sometimes|date',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'status' => 'in:pending,confirmed,cancelled',
            'notes' => 'nullable|string',
        ]);

        $appointment->update($data);
        return $appointment;
    }

    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
