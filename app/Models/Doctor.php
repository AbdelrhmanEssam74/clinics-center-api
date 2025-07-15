<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [

        'specialty_id',
        'user_id',
        'experience_years',
        'appointment_fee', // Added appointment_fee field
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class)->with('role');
    }

// relationship with Specialty
    public function specialty()
    {
        return $this->belongsTo(Specialty::class);
    }
    // relationship with slots
    public function Slot()
    {
        return $this->hasMany(Slot::class, 'doctor_id');
    }
}
