<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $fillable = [
        'gender',
        'phone',
        'address',
        'date_of_birth',
        'user_id',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function doctors()
    {
        return $this->belongsToMany( Doctor::class);
    }
}
