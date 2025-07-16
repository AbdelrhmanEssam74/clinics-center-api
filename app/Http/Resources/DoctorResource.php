<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [

            'id' => $this->id,
            'user_id' => $this->user_id,
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'image' =>asset( $this->user->image) ,
            'appointment_fee' => $this->appointment_fee, // Include appointment fee
            'profile_description' => $this->user->profile_description,
            'specialty' => [
                'id' => $this->specialty->id,
                'name' => $this->specialty->name,
            ],
            'years_of_experience' => $this->experience_years,
             'available_slots' => SlotResource::collection(
            $this->Slot()
                ->where('status', 'available')
                ->where('date', '>=', now())
                ->orderBy('date')
                ->orderBy('start_time')
                ->get()
        ),
        ];
    }
}
