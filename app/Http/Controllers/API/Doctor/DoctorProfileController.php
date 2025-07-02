<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDoctorProfileRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DoctorProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->doctor) {
            return response()->json(['message' => 'User is not a doctor'], 403);
        }

        $doctor = Doctor::with(['user.role', 'specialty'])
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'doctor' => $doctor,
            'user' => $user,
        ]);
    }

    public function update(UpdateDoctorProfileRequest $request)
    {
        $user = Auth::user();

        if (!$user->doctor) {
            return response()->json([
                'message' => 'Unauthenticated. Please login and try again.',
                'status' => 'error',
            ], 401);
        }
        $user->update($request->only([
            'name',
            'email',
            'phone',
            'image',
            'profile_description'
        ]));

        $user->doctor->update($request->only([
            'specialty_id',
            'experience_years'
        ]));
        
        $doctor = Doctor::with(['user.role', 'specialty'])
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'message' => 'Profile updated successfully',
            'status' => 'success',
            'doctor' => $doctor,
            'user' => $user,
        ]);
    }
}
