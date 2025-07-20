<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Slot;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboardData()
    {
        return response()->json([
            'patients_count' => Patient::count(),
            'doctors_count' => Doctor::count(),
            'appointments_today' => Appointment::whereDate('appointment_date', now()->toDateString())->count(),
            'available_slots' => Slot::where('status', 'available')->count(),
            'users_count' => User::count() ,

        ]);
    }
}
