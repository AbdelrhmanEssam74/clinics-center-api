<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
class DoctorController extends Controller
{
    public function index()
    {
        return Doctor::with('Slot')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'specialization' => 'required|string',
            'phone' => 'required|string',
            'email' => 'required|email|unique:doctors'
        ]);

        return Doctor::create($data);
    }

    public function show($id)
    {
        return Doctor::with('Slot')->findOrFail($id);
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
