<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;

class PatientController extends Controller
{
    public function index(Request $request)
    {
        $query = Patient::with('user');

        if ($request->filled('name')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->name . '%');
            });
        }

        return $query->get();
    }





    public function show($id)
    {
        return Patient::with('user')->findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $patient = Patient::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes',
            'gender' => 'sometimes|in:male,female',
            'birth_date' => 'sometimes|date',
            'phone' => 'sometimes',
            'address' => 'nullable',
            'medical_record_number' => 'sometimes|unique:patients,medical_record_number,' . $id
        ]);

        $patient->update($data);
        return $patient;
    }

    public function destroy($id)
    {
        $patient = Patient::findOrFail($id);
        $patient->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
