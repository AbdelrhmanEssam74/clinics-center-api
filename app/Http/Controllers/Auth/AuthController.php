<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Models\Patient;
use App\Models\User;

class AuthController extends Controller
{


    public function register(StoreUserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $filename, 'public');
            $data['image'] = 'storage/'. $path;            
        }

        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = $patient['role_id'] ?? 5;
        $data['profile_description'] = $data['profile_description'] ?? 'Patient'; 

        $user = User::create($data);
        // 
        $patient = new Patient();
        $patient->user_id = $user->id;
        $patient->medical_record_number = 'MRN' . time(); 
        $patient->gender = $data['gender'] ; 
        $patient->address = $data['address'] ; 
        $patient->phone = $data['phone'] ; 
        $patient->date_of_birth = $data['date_of_birth'] ?? '2000-01-01'; 
        
        $patient->save();
    if ($user->image) {
        $user->image = asset($user->image);
    }
        // 

        $user = new UserResource($user);
        $token = $user->createToken('auth_token')->plainTextToken;
        return response()->json([
            "user" => $user,
            "token" => $token
        ]);
    }

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'user'  => $user,
            'token' => $token
        ]);
    }

    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }
        return new UserResource($user);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['message' => 'Logged out']);
    }
}
