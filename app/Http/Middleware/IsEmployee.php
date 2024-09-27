<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsEmployee
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (Gate::denies('isEmployee')){
            return response()->json([
                'message' => 'لا يوجد صلاحيات فانت لست موظف'
            ], 403);
        }

        return $next($request);
    }
}
