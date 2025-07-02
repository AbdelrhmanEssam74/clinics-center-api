<?php

namespace App\Http\Controllers\API\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Slot;
use App\Models\Specialty;
use Carbon\Carbon;

class SearchController extends Controller
{

    public function index()
    {
        $doctors = DoctorResource::collection(Doctor::paginate(3));
        return response()->json(
            [
                "doctors" => $doctors
            ], 200);
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
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Specialty not found'
                ], 404
            );
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
        return response()->json([
            'success' => true,
            'data' => DoctorResource::collection($doctors)
        ] , 200);
    }


    public function getAvailTimeSlots($id)
    {
        $slots = Slot::where('doctor_id', $id)
            ->where('status', 'available')
            ->whereDate('date', '>=', Carbon::today())
            ->get();
        if ($slots->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No available time slots found for this doctor'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $slots
        ], 200);
    }

}
