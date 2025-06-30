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
            'name' => $this->user->name,
            'email' => $this->user->email,
            'phone' => $this->user->phone,
            'image' => $this->user->image ,
            'profile_description' => $this->user->profile_description,
            'specialty' => [
                'id' => $this->specialty->id,
                'name' => $this->specialty->name,
            ],
            'experience_years' => $this->experience_years,
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
