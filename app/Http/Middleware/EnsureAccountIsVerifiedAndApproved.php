<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureAccountIsVerifiedAndApproved
{
    
    public function handle(Request $request, Closure $next): Response
    {
        $account = Auth::user()->account;

        if (!$account || !$account->email_verified_at || $account->status !== 'Approved') {
            return response()->json(['message' => 'Your account must be verified and approved to proceed.'], 403);
        }

        return $next($request);
    }
}
