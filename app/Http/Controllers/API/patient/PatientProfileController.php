<?php

namespace App\Http\Controllers\API\patient;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePatientProfileRequest;
use App\Models\Patient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class PatientProfileController extends Controller
{

    public function show()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated. Please login and try again.',
            ], 401);
        }

        $patient = $user->patient;

        if ($user->image) {
            $user->image = asset($user->image);
        }

        if (!Gate::allows('manage-profile', $patient)) {
            abort(403, 'Unauthorized access to patient profile.');
        }

        return response()->json([
            'Patient Profile' => $patient,
            'User' => $user,
        ], 200);
    }


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

        if (!Gate::allows('manage-profile', $patient)) {
            abort(403, 'Unauthorized access.');
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
        ]);

        return response()->json([
            'message' => 'Patient profile updated successfully.',
            'Patient Profile' => $patient,
            'User' => $user,
        ], 200);
    }


    public function updateImage(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['message' => 'Unauthenticated.'], 401);
        }

        if ($request->hasFile('image')) {
            if ($user->image) {
                $oldImage = str_replace('storage/users/', '', $user->image);
                Storage::disk('public')->delete('users/' . $oldImage);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('users', $filename, 'public');

            $user->image = 'storage/users/' . $filename;
            $user->save();

            return response()->json([
                'success' => true,
                'path' => asset($user->image),
            ]);
        }

        return response()->json(['message' => 'No image uploaded.'], 400);
    }
}
