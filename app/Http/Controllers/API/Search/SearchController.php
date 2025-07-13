<?php

namespace App\Http\Controllers\API\Search;

use App\Http\Controllers\Controller;
use App\Http\Resources\DoctorResource;
use App\Models\Doctor;
use App\Models\Slot;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function index()
    {
        $doctors = DoctorResource::collection(Doctor::all());
        return response()->json(
            [
                "doctors" => $doctors
            ],
            200
        );
    }



    public function getById($id)
    {
        $doctor = Doctor::with(['user', 'specialty'])
            ->where('id', $id)
            ->first();

        if (!$doctor) {
            return response()->json([
                'success' => false,
                'message' => 'Doctor not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => new DoctorResource($doctor)
        ], 200);
    }


    public function search($searchTerm)
    {
        $searchTerm = trim($searchTerm);


        if (empty($searchTerm)) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a search term'
            ], 400);
        }

        $doctors = Doctor::with(['user', 'specialty'])
            ->whereHas('specialty', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->orWhereHas('user', function ($query) use ($searchTerm) {
                $query->where('name', 'like', '%' . $searchTerm . '%');
            })
            ->get();

        if ($doctors->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No doctors found matching your search'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => DoctorResource::collection($doctors)
        ], 200);
    }


    public function getAvailTimeSlots($id)
    {
        $slots = Slot::where('doctor_id', $id)
            ->where('status', 'available')
            ->whereDate('date', '>=', Carbon::today())
            ->get()
            ->groupBy('date');
            
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
