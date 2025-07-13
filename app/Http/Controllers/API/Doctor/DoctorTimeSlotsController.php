<?php
namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class DoctorTimeSlotsController extends Controller
{
    public function index(Request $request)
    {
        $doctor = auth()->user();
        $timeSlots = Slot::where('doctor_id', $doctor->id)
            ->orderBy('date', 'asc')
            ->orderBy('start_time', 'asc')
            ->get();
        return response()->json([
            'success' => true,
            'message' => 'Time slots retrieved successfully',
            'slots' => $timeSlots,
        ]);
    }

    public function store(Request $request)
    {
        $doctor = auth()->user();
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s|before:end_time',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);
        $slot = Slot::create([
            'doctor_id' => $doctor->id,
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

    public function update(Request $request, $id)
    {
        $doctor = auth()->user();
        if (!is_numeric($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid slot ID.',
            ], 400);
        }
        $slot = Slot::where('id', $id)->where('doctor_id', $doctor->id)->first();
        if (!$slot) {
            return response()->json([
                'success' => false,
                'message' => 'Time slot not found or does not belong to the doctor',
            ], 404);
        }
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'start_time' => 'required|date_format:H:i:s|before:end_time',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'status' => 'sometimes|in:available,booked,cancelled',
        ]);
        $slot->update([
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'status' => $request->status ?? $slot->status,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Time slot updated successfully',
            'data' => $slot,
        ]);
    }
public function show($id)
{
    $doctor = auth()->user();
   // Validate the ID is numeric
    if (!is_numeric($id)) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid slot ID.',
        ], 400);
    }
    // Find the slot that belongs to the doctor
    $slot = Slot::where('id', $id)
        ->where('doctor_id', $doctor->id)
        ->first();
    if (!$slot) {
        return response()->json([
            'success' => false,
            'message' => 'Time slot not found or does not belong to the doctor',
        ], 404);
    }
    return response()->json([
        'success' => true,
        'message' => 'Time slot retrieved successfully',
        'data' => $slot,
    ]);
}

    public function destroy($id)
    {
        $doctor = auth()->user();
       if (!is_numeric($id)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid slot ID.',
            ], 400);
        }
        $slot = Slot::where('id', $id)->where('doctor_id', $doctor->id)->first();
        if (!$slot) {
            return response()->json([
                'success' => false,
                'message' => 'Time slot not found or does not belong to the doctor',
            ], 404);
        }
        $slot->delete();
        return response()->json([
            'success' => true,
            'message' => 'Time slot deleted successfully',
        ]);
    }
}
