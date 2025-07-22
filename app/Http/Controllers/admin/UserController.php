<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        if ($request->has('name')) {
            $query->where('name', 'like', '%' . $request->name . '%');
        }

        $users = $query->get();  
        return UserResource::collection($users);
    }

    public function store(StoreUserRequest $request)
    {

        $data = $request->validated();
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $filename, 'public');
            $data['image'] = 'storage/users/' . $filename;            
        }
        $data['password'] = Hash::make($data['password']);
        $data['role_id'] = $data['role_id'] ?? 5;
     $data['profile_description'] = $data['profile_description']
    ?? ($data['role_id'] == 2 ? 'Doctor' : ($data['role_id'] == 1 ? 'Admin' : 'Patient'));


        $user = User::create($data);


    if ($data['role_id'] == 2) { 
        $this->Doctor($user, $request);
    }
    elseif ($data['role_id'] == 5) { 
        $this->Patient($user, $data);
    } elseif($data['role_id'] == 1){
        $this->admin($user, $data);
    }

    elseif ($data['role_id'] == 1) {
    $this->Admin($user, $data);
}


        if ($user->image) {
            $user->image = asset($user->image);
        }
        $user = new UserResource($user);
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            "user" => $user,
            "token" => $token
        ]);
    }

        // handle doctor  
    public function Doctor(User $user, Request $request)
    {
        $doctor = new Doctor();

    if ($request->hasFile('license_file')) {
        $file = $request->file('license_file');

        $name = $user->name; 
        $filename = 'Dr'.$name.'_license_' . $file->getClientOriginalName();
        
        $path = $file->storeAs('licenses', $filename, 'public');
        $doctor['license_file'] = 'storage/licenses/' . $filename;
    }
    
        $doctor->user_id = $user->id;
        $doctor->specialty_id = $request['specialty_id'] ?? '1';
        $doctor->experience_years = $request['experience_years'] ?? '1';
        $doctor->appointment_fee = $request['appointment_fee'] ?? '100';
        $doctor->status = $request['status'] ??'pending';
    
        $doctor->save();

    return response()->json([
        "doctorData" => $doctor,
    ]);
}

    // handle patient 
    public function Patient(User $user, array $request){
        
        $patient = new Patient();
        $patient->user_id = $user->id;
        $patient->medical_record_number = 'MRN' . time();
        $patient->gender = $request['gender'];
        $patient->address = $request['address'];
        $patient->phone = $request['phone'];
        $patient->date_of_birth = $request['date_of_birth'] ?? '2000-01-01';
        $patient->save();

        return response()->json([
        "patient" => $patient,
    ]);
    }

    // handle admin 
    public function Admin(User $admin, array $request){
           
        $admin->name = $request['name'] ;
        $admin->email = $request['email'] ;
        $admin->save();

        return response()->json([
        "admin" => $admin,
    ]);
    }

    public function show($id)
    {
        return User::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|string|email|max:255|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
            'phone' => 'sometimes|regex:/^[0-9]{10,15}$/',
            'role_id' => 'sometimes|integer|exists:roles,id',
            'address' => 'sometimes|string|max:255',
            'gender' => 'sometimes|string|in:Male,Female',
            'date_of_birth' => 'sometimes|date',
            'profile_description' => 'sometimes|string',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('users', $filename, 'public');
            $data['image'] = 'storage/users/' . $filename;
        }

        $user->update($data);

        $user->image = $user->image ? asset($user->image) : null;

        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('role')
        ]);
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return response()->json(['message' => 'User deleted']);
    }
}
