<?php

namespace App\Http\Controllers\API\patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePatientProfileRequest;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PatientProfileController extends Controller
{
    /**
     * Display the authenticated user's patient profile.
     *
     * @return \Illuminate\Http\JsonResponse
     */

    public function show()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated. Please login and try again.',
                'status' => 'error',
            ], 401);
        }
        $patient = $user->patient;

        // check Authorization
        if (! Gate::allows('manage-profile', $patient)) {
            abort(403, 'error,Unauthorized');
        }
        return response()->json([
            'Patient Profile' => $patient,
            'User' => $user,
        ], 200);
    }

    // update patient profile

    public function update(UpdatePatientProfileRequest $request)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated. Please login and try again.',
                'status' => 'error',
            ], 401);
        }

        $patient = $user->patient;
        if (!$patient) {
            return response()->json([
                'message' => 'Patient profile not found for this user.',
                'status' => 'error',
            ], 404);
        }

        // check Authorization
        if (! Gate::allows('manage-profile', $patient)) {
            abort(403, 'error,Unauthorized');
        }           
        $imagePath = $user->image;
        if ($request->hasFile('image')) {
            if ($user->image && Storage::exists(str_replace('storage/', 'public/', $user->image))) {
                Storage::delete(str_replace('storage/', 'public/', $user->image));
            }    
            $imagePath = 'storage/' . $request->file('image')->store('profile_images', 'public');
        }



        $patient->update([
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'address' => $request->address,

        ]);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Patient profile updated successfully.',
            'Patient Profile' => $patient,
            'User' => $user,
        ], 200);
    }
}