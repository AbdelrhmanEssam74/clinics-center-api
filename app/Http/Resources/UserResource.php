<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'profile_description' => $this->profile_description,
            'role' => [
                'id' => $this->role_id,
                'name' => optional($this->role)->name,
            ],
            'created_at' => optional($this->created_at)->toDateTimeString(),
        ];
    }
}
