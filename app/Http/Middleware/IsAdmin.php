<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (Gate::denies('isAdmin')){
            return response()->json([
                'message' => 'Unauthorized , you are not Admin'
            ], 403);
        }

        return $next($request);
    }
}
