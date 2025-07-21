<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class DoctorHomeController extends Controller
{
public function index()
{
    $doctors = Doctor::with('user:id,name,image')
        ->take(4) 
        ->get()
        ->map(function ($doctor) {
            return [
                'id' => $doctor->id,
                'name' => $doctor->user->name ?? 'Unnamed',

                'specialty' => $doctor->specialty,
                'image' => $doctor->user->image ? asset( $doctor->user->image) : null,
                'social' => [  
                    'fa-brands fa-facebook-f',
                    'fa-brands fa-twitter',
                    'fa-brands fa-google',
                    'fa-brands fa-instagram',
                ]
            ];
        });

    return response()->json($doctors);
}

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
