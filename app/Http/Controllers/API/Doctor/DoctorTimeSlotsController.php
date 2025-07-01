<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class DoctorTimeSlotsController extends Controller
{
    public function index(Request $request)
    {
        $doctor_id = 1;
        $timeSlots = Slot::where('doctor_id', $doctor_id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Time slots retrieved successfully',
            'data' => $timeSlots,
        ]);
    }

    // add a new time slot
    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s|before:end_time',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);
        // store the request data
        $slot = Slot::create([
            'doctor_id' => 1,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => 'available',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Time slot created successfully',
            'data' => $slot,
        ]);
    }

    // delete a time slot
    public function destroy($id)
    {
        $doctor_id = 1;
        // check if the slot exists and belongs to the doctor
        $slot = Slot::where('id', $id)->where('doctor_id', $doctor_id)->first();
        if (!$slot) {
            return response()->json([
                'success' => false,
                'message' => 'Time slot not found or does not belong to the doctor',
            ], 404);
        }
        // delete the slot
        $slot->delete();
        return response()->json([
            'success' => true,
            'message' => 'Time slot deleted successfully',
        ]);
    }
}
