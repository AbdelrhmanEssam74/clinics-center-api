<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user() && $request->user()->role_id == 1) {
            return $next($request);
        }

        return response()->json(['message' => 'Unauthorized'], 403);
    }
}
