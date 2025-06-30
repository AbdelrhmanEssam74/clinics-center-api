<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    //
    protected $fillable = ['name', 'description'];
    // Define the relationship with Doctor
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
