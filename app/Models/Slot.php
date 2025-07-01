<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'status',
    ];
    // relationship with Doctor model
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
