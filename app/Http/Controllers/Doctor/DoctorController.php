<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Http\Resources\SlotResource;
use App\Models\Doctor;
use App\Models\Slot;
use App\Models\Specialty;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    
    public function index()
    {
        $doctors = DoctorResource::collection(Doctor::paginate(4));
        return response()->json($doctors);
    }
    // search should hanbled by one function to filter
    //  retertiving data even by name or speciality
    //  sorry but i will keep it as it is for now 
        
    
    //   by speciality
    public function getBySpecialty($specialty)
    {
        $specialty = trim($specialty);
    
        $specialty = Specialty::where('name', 'like', $specialty)->first();
        
        if (!$specialty) {
        return response()->json([
            'success' => false,
            'message' => 'Specialty not found'
        ], 404);
    }
        $doctors = Doctor::with(['user', 'specialty'])->where('specialty_id', $specialty->id)
        ->get();
        if ($doctors->isEmpty()) {
            return response()->json(['message' => 'No doctors found for this specialty'], 404);
        }

        return response()->json(DoctorResource::collection($doctors));
    }
    // by name
 public function getByName($name)
{

    $doctors = Doctor::with(['user', 'specialty'])
        ->whereHas('user', function ($query) use ($name) {
            $query->where('name', 'like', '%' . $name . '%');
        })
        ->get();

    if ($doctors->isEmpty()) {
        return response()->json([
            'success' => false,
            'message' => 'No doctors found with this name'
        ], 404);
    }

    return response()->json(DoctorResource::collection($doctors));
}

    
    public function getAvailTimeSlots(Doctor $doctor){
        $slots = Slot::where('doctor_id', $doctor->id)
                    ->where('status', 'available')
                    ->where('date', '>=', now())
                    ->orderBy('date')
                    ->orderBy('start_time')
                    ->get();
            // dd($slots);
        return response()->json([
            'success' => true,
            'data' => (DoctorResource::collection($slots))
            
        ]);
    }
}
