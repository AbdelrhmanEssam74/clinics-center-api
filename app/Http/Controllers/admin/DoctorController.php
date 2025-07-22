<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;

class DoctorController extends Controller
{
    public function index(Request $request)
    {
        // $query = Doctor::with(['Slot', 'user','specialty']) ->where('status', 'pending')
        //         ->get();
        $query = Doctor::with(['Slot', 'user']);

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        return $query->get();
    }



    public function show($id)
    {
        return Doctor::with(['Slot', 'user'])->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string',
            'specialization' => 'sometimes|string',
            'phone' => 'sometimes|string',
            'email' => 'sometimes|email|unique:doctors,email,' . $id
        ]);

        $doctor->update($data);
        return $doctor;
    }

    public function destroy($id)
    {
        Doctor::findOrFail($id)->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
