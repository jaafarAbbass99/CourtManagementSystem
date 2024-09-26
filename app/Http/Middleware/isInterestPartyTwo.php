<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class isInterestPartyTwo
{
    
    public function handle(Request $request, Closure $next): Response
    {
        
        if (Gate::denies('isInterestAsPartyTwo',$request->case_id)){
            return response()->json([
                'message' => 'انت لست مهتم كطرف دفاع بالقضية'
            ], 403);
        }

        return $next($request);
    }
}
