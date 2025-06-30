<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $fillable = [

        'specialization',
       'user_id',
        'address',
    ];

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function patients()
    {
        return $this->belongsToMany(Patient::class);
    }
    // Doctor.php
public function user()
{
    return $this->belongsTo(User::class);
}
public function specialty()
{
    return $this->belongsTo(Specialty::class, 'specialty_id', 'id');

}
}
