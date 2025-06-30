<?php

namespace App\Http\Controllers\API\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function APITest()
    {
        return response()->json([
            'message' => 'API is working',
            'status' => 'success'
        ]);
    }
}
