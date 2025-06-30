<?php

namespace App\Http\Controllers;
use App\Http\Requests\CreateAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $appointments = Appointment::all();
        return AppointmentResource::collection($appointments);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->validated());
        return new AppointmentResource($appointment);
    }

    /**
     * Display the specified resource.
     */
   public function show(string $id)
{
    $appointment = Appointment::find($id);

    if (!$appointment) {
        return response()->json([
            'message' => "This appointment was not found."
        ], 404);
    }

    return new AppointmentResource($appointment);
}

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAppointmentRequest $request, string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->validated());
        return new AppointmentResource($appointment);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();
       return response()->json(['message' => "Appointment Deleted Succsfully"]);
    }
}
