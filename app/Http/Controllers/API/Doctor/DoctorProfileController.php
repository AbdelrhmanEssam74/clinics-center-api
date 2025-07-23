<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateDoctorProfileRequest;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

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
        // Update user fields
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');
        $user->profile_description = $request->input('profile_description');
        $user->save();
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
public function updateImage(Request $request)
{
    if ($request->hasFile('image')) {
        $url = $this->storeImage($request->file('image'));

        $user = Auth::user();
        $user->image = $url;
        $user->save();

        return response()->json([
            'success' => true,
            'path' => $url
        ]);
    }

    return response()->json([
        'success' => false,
        'message' => 'No image uploaded',
    ], 422);
}



private function storeImage($file, $folder = 'users')
{
    $filename = time() . '_' . $file->getClientOriginalName();
    $file->storeAs($folder, $filename, 'public');
    return asset("storage/{$folder}/{$filename}");
}

}
