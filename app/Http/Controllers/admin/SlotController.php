<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Slot;
use Illuminate\Http\Request;

class SlotController extends Controller
{
    public function index()
    {
        return Slot::with('doctor')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date',
            'start_time' => 'required',
            'end_time' => 'required',
            'status' => 'in:available,booked,cancelled'
        ]);

        return Slot::create($data);
    }

    public function show($id)
    {
        return Slot::with('doctor')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $slot = Slot::findOrFail($id);

        $data = $request->validate([
            'date' => 'sometimes|date',
            'start_time' => 'sometimes',
            'end_time' => 'sometimes',
            'status' => 'in:available,booked,cancelled',
            'doctor_id' => 'sometimes|exists:doctors,id'
        ]);

        $slot->update($data);
        return $slot;
    }

    public function destroy($id)
    {
        Slot::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
