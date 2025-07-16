<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MedicalReport extends Model
{
    protected $fillable = [
        'patient_id',
        'title',
        'file_path',
        'description',
        'report_date'
    ];
    public function patient(){
        return $this->belongsTo(Patient::class);
    }
}
