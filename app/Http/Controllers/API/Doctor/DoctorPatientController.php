<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Illuminate\Http\Request;

class DoctorPatientController extends Controller
{
    // get all patients which had appointments with the doctor
    public function index(Request $request)
    {
        $doctor = auth()->user();
        $patients = Appointment::select('doctor_id'  , 'patient_id')->where('doctor_id', $doctor->id)
            ->with(['patient.user:id,email,name,image'])
            ->get();

        return response()->json([
            'message' => 'List of patients retrieved successfully',
            'data' => $patients
        ]);
    }
    // get specific patient by id
    public function show($id)
    {
        $patient = Appointment::where('doctor_id', 1)
            ->whereHas('patient', function ($query) use ($id) {
                $query->where('id', $id);
            })
            ->with(['patient.user:id,email,name'])
            ->first();

        if (!$patient) {
            return response()->json([
                'message' => 'Patient not found'
            ], 404);
        }

        return response()->json([
            'message' => 'Patient retrieved successfully',
            'data' => $patient
        ]);
    }
}
