<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorHomeController extends Controller
{
    public function home()
    {
        $user = auth()->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        if ($user->role_id !== 2) {
            return response()->json(['message' => 'Forbidden: Not a doctor'], 403);
        }
        $user_id = $user->id;
        $doctor = Doctor::select('user_id','specialty_id','experience_years')->with([
        'specialty:id,name',
         'user:id,role_id,name,email,phone,image,profile_description,password'
         ])
            ->find($user_id);

        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }

        return response()->json([
            'doctor' => $doctor
        ], 200);
    }
}
