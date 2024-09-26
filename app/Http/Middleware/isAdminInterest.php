<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isAdminInterest
{
    
    public function handle(Request $request, Closure $next): Response
    {
        
        if (Gate::denies('isAbleAttorneys',$request->case_id)){
            return response()->json([
                'message' => 'انت ليس admin التوكيل'
            ], 403);
        }

        return $next($request);
    }
}
