<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

    return [
            'patient' => [
                'id' => $this->patient->id ?? null,
                'gender' => $this->patient->gender ?? null,
                'phone' => $this->patient->phone ?? null,
                'user_id' => $this->patient->user_id ?? null,
                'address' => $this->patient->address ?? null,
                'date_of_birth' => $this->patient->date_of_birth ?? null,
                'appointments' => $this->patient->appointments->map(function ($appointment) {
                    return [
                        'id' => $appointment->id,
                        'appointment_date' => $appointment->appointment_date,
                        'start_time' => $appointment->start_time,
                        'end_time' => $appointment->end_time,
                        'status' => $appointment->status,
                        'notes' => $appointment->notes,
                    ];
                }),
            ],



            'doctor' => $this->doctor ? [
                'id' => $this->doctor->id,
        'user_id' => $this->doctor->user_id ?? null,

                'specialization' => $this->doctor->specialization_id,
            ] : null
        ];
    }
}
