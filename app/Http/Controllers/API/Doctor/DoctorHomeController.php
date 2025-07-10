<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorHomeController extends Controller
{
    public function home($id)
    {
        $user = auth()->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if ($user->role_id !== 2) {
            return response()->json(['message' => 'Forbidden: Not a doctor'], 403);
        }

        if ($user->id != $id) {
            return response()->json(['message' => 'Forbidden: ID mismatch'], 403);
        }

        $doctor = Doctor::with(['specialty', 'appointments', 'user', 'user.role'])
            ->find($id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json([
            'doctor' => $doctor
        ], 200);
    }
}
