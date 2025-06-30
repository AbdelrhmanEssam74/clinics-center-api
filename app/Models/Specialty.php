<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Specialty extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    /**
     * Get the doctors associated with the specialty.
     */
    public function doctors()
    {
        return $this->hasMany(Doctor::class);
    }
}
