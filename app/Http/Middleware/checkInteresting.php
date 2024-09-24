<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class checkInteresting
{
    
    public function handle(Request $request, Closure $next): Response
    {
        
        if (Gate::denies('isInterestesCase',$request->case_id)){
            return response()->json([
                'message' => 'انت غير مهتم بالقضية'
            ], 403);
        }

        return $next($request);
    }
}
