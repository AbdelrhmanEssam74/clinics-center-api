<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home($id)
    {
        $doctor = Doctor::with(['specialty', 'appointments' , 'user', 'user.role'])
            ->find($id);
        if (!$doctor) {
            return response()->json(['message' => 'Doctor not found'], 404);
        }
        return response()->json([
            'doctor' => $doctor
        ], 200);
    }
}
