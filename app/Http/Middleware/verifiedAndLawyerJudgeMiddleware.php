<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class verifiedAndLawyerJudgeMiddleware
{
    
    public function handle(Request $request, Closure $next): Response
    {
        if (Gate::denies('verified_Lawyer_Judge')){
            throw new AuthenticationException();
            // return response()->json([
            //     'message' => 'Unauthorized'
            // ], 403);
        }
        return $next($request);
    }
}
