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
                'image' =>  asset('storage/'.$admin->image),
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

        return response()->json(
            [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'image' => $admin->image ? asset('storage/' . $admin->image) : asset('storage/defaults/admin.png'),
                'phone' => $admin->phone,
                // 'address' => $admin->address,
                // 'gender' => $admin->gender,
                // 'date_of_birth' => $admin->date_of_birth,
                'profile_description' => $admin->profile_description,
                'role' => $admin->role->name,
            ]
        );
    }

    // update
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

            $path = $request->file('image')->store('admin', 'public');
            $validated['image'] = 'storage/' . $path;
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
        // delete old image
        if ($request->hasFile('image')) {
            if ($user->image) {
                $oldImage = str_replace('storage/users/', '', $user->image);
                Storage::disk('public')->delete('users/' . $oldImage);
            }
        }
            // store
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('admin', $filename, 'public');

            $user->image = 'storage/admin/' . $filename;
            $user->save();
        
            return response()->json([
            'success' => true,
            'path' => asset($user->image)
        ]);    
    }
}
