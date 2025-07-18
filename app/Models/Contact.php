<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'subject',
        'phone',
        'message',
    ];

    // You can add any additional methods or relationships here if needed
}
