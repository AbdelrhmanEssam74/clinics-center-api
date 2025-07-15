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

        return [
            'patient' => [
                'id' => $this->patient->id ?? null,
                'name' => $this->patient->user->name ?? null,
                'gender' => $this->patient->gender ?? null,
                'phone' => $this->patient->phone ?? null,
                'address' => $this->patient->address ?? null,
                'date_of_birth' => $this->patient->date_of_birth ?? null,
            ],

            'appointment' => [
                'id' => $this->id,
                'appointment_date' => $this->appointment_date,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'status' => $this->status,
                'notes' => $this->notes,
                'payment_method' => $this->payment_method ?? null,
                'payment_status' => $this->payment_status ?? null,
            ],

            'doctor' => $this->doctor ? [
    'id' => $this->doctor->id,
    'name' => $this->doctor->user->name ?? null,
    'specialization' => $this->doctor->specialty->name ?? null,
    'phone' => $this->doctor->user->phone ?? null,
    'appointment_fee' => $this->doctor->appointment_fee ?? null, // Include appointment fee
] : null,
        ];
    }
}
