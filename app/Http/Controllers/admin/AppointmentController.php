<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
class AppointmentController extends Controller
{
   public function index(Request $request)
{
    $query = Appointment::with(['doctor', 'patient']);

    if ($request->has('status')) {
        $query->where('status', $request->status);
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
            'notes' => 'nullable|string'
        ]);

        return Appointment::create($data);
    }

    public function show($id)
    {
        return Appointment::with(['doctor', 'patient'])->findOrFail($id);
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
            'notes' => 'nullable|string'
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
