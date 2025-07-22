<?php

namespace App\Http\Controllers\Auth;

use App\Http\Resources\UserResource;

use App\Http\Controllers\Controller;
use App\Http\Requests\addDoctorRequest;
use App\Http\Requests\addPatientRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\SlotResource;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Slot;
use App\Models\Specialty;
use App\Models\User;
use App\Mail\DoctorAccountUnderReview;
use Illuminate\Support\Facades\Mail;
class AuthController extends Controller
{
    public function register(StoreUserRequest $request)
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
        $data['profile_description'] = $data['profile_description'] ??
            ($data['role_id'] == 2 ? 'Doctor' : 'Patient');
        $user = User::create($data);
        if ($data['role_id'] == 2) {
            $this->Doctor($user, $request);
            Mail::to($user->email)->send(new DoctorAccountUnderReview($user, 'pending'));
        } else {
            $this->Patient($user, $data);
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
            $filename = 'Dr' . $name . '_license_' . $file->getClientOriginalName();

            $path = $file->storeAs('licenses', $filename, 'public');
            $doctor['license_file'] = 'storage/licenses/' . $filename;
        }
        $doctor->user_id = $user->id;
        $doctor->specialty_id = $request['specialty_id'] ?? '1';
        $doctor->experience_years = $request['experience_years'] ?? '1';
        $doctor->appointment_fee = $request['appointment_fee'] ?? '100';
        $doctor->status = $request['status'] ?? 'pending';
        $doctor->save();
        return response()->json([
            "doctorData" => $doctor,
        ]);
    }

    // handle patient
    public function Patient(User $user, array $request)
    {

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

    public function login(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        $token = $user->createToken('auth_token')->plainTextToken;
        // if role id == 2 then check if doctor is active
        if ($user && $user->role_id == 2 && $user->doctor && $user->doctor->status != 'active') {
            return response()->json([
                'user' => $user,
                'message' => 'Doctor account is not active',
                'status_code' => 403
            ], 403);
        }
        return response()->json([
            'user' => $user,
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
    public function getSpecialties()
    {
        $Specialty = Specialty::select('id', 'name')->get();
        return response()->json(
            [
                "specialties" => $Specialty
            ],
            200
        );
    }
}
