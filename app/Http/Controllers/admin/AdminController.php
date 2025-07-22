<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Storage;

class AdminController extends Controller
{
    public function dashboardData()
    {
        $admin = Auth::user();

        if (!$admin) {
            return response()->json(['error' => 'Admin not authenticated'], 401);
        }

        return response()->json([
            'stats' => [
                'patients_count' => Patient::count(),
                'doctors_count' => Doctor::count(),
                'appointments_today' => Appointment::whereDate('appointment_date', now()->toDateString())->count(),
                'available_slots' => Slot::where('status', 'available')->count(),
                'users_count' => User::count(),
            ],
            'admin' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'image' => $admin->image ? asset($admin->image) : asset('storage/defaults/admin.png'),
                'role' => $admin->role->name,
            ],
        ]);
    }

    public function show()
    {
        $admin = Auth::user();

        if (!$admin) {
            return response()->json(['error' => 'Admin not authenticated'], 401);
        }

        return response()->json([
            'id' => $admin->id,
            'name' => $admin->name,
            'email' => $admin->email,
            'image' => $admin->image ? asset($admin->image) : asset('storage/defaults/admin.png'),
            'phone' => $admin->phone,
            'profile_description' => $admin->profile_description,
            'role' => $admin->role->name,
        ]);
    }

    public function updateAdminProfile(Request $request)
    {
        $admin = Auth::user();

        $data = [
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:users,email,' . $admin->id,
            'phone' => 'sometimes|string|max:20',
            'profile_description' => 'sometimes|string|max:1000',
            'current_password' => 'sometimes|required_with:new_password|current_password:sanctum',
            'new_password' => 'sometimes|required_with:current_password|min:8',
            'new_password_confirmation' => 'sometimes|required_with:new_password|same:new_password'
        ];

        $validated = $request->validate($data);

        if ($request->has('current_password') && $request->has('new_password')) {
            $admin->update([
                'password' => bcrypt($validated['new_password'])
            ]);
        }

        if ($request->hasFile('image')) {
            if ($admin->image && Storage::exists($admin->image)) {
                Storage::delete($admin->image);
            }

            $path = $request->file('image')->storeAs('users', time() . '_' . $request->file('image')->getClientOriginalName(), 'public');
            $validated['image'] = 'storage/users/' . basename($path);
        }

        $profileData = collect($validated)->except([
            'current_password',
            'new_password',
            'new_password_confirmation'
        ])->toArray();

        $admin->update($profileData);

        return response()->json([
            'message' => 'Profile updated successfully',
        ]);
    }

    public function updateImage(Request $request)
    {
        $user = Auth::user();

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
                'path' => asset($user->image)
            ]);
        }

        return response()->json(['message' => 'No image uploaded'], 400);
    }


    // mamage recently registered doctors
    public function pendingDoctors()
{

    $doctors = Doctor::with(['user', 'specialty'])
                ->where('status', 'pending')
                ->get();
    
    return response()->json($doctors);
}

public function approveDoctor(Doctor $doctor)
{
    $doctor->update(['status' => '	accepted']);
    
    return response()->json(['message' => 'Doctor approved successfully']);
}

public function rejectDoctor(Request $request, Doctor $doctor)
{
    $request->validate(['reason' => 'required|string']);
    
    $doctor->update([
        'status' => 'rejected',
        'rejection_reason' => $request->reason
    ]);
    
    return response()->json(['message' => 'Doctor rejected']);
}


    // for doctor dispaly
public function getDoctorLicence(Doctor $doctor)
{
    if (!$doctor->license_file) {
        return response()->json([
            'message' => 'No license file found for this doctor',
        ], 404);
    }

    $licenseUrl = asset($doctor->license_file);
    
   
    return response()->json([
            'license_url' => $licenseUrl,
    ]);
}
}
