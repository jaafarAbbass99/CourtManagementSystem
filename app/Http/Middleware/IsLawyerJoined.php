<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsLawyerJoined
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $court_id = $request->input('my_court_id')!=null ? $request->input('my_court_id') :  $request->route('court_id') ; 
        
        
        if (!$court_id) {
            return response()->json([
                'message' => 'Court ID is required'
            ], 400);
        }

        if (Gate::denies('isLawyerJoinedToCourt',$court_id)){
            return response()->json([
                'message' => 'Unauthorized , you is not joined to this court'
            ], 403);
        }

        return $next($request);
    }
}
