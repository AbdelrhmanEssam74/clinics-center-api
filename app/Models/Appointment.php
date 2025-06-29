<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'appointment_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    public function patient()
    {
        return $this->belongsTo('App\Models\Patient');
    }

    public function doctor()
    {
        return $this->belongsTo('App\Models\Doctor');
    }
}
