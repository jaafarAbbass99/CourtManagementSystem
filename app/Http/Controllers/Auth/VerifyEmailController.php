<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VerifyEmailController extends Controller
{
    
    public function __invoke($id , $hash): JsonResponse
    {
        $user = Account::findOrFail($id);

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return response()->json(['message' => 'Invalid verification link.'], 400);
        }
        
        if ($user->hasVerifiedEmail()) {
            return response()->json(['message' => $user->hasVerifiedEmail()], 400);
        }
        
        
        if($user->markEmailAsVerified() && $user->user->role === 'User' ){
            $this->makeAccountIsApproved($user);
        }


        return response()->json([
            'Email verified successfully.'
            ], 200);
    }


    private function makeAccountIsApproved($account)
    {
        $account->status = "Approved";
        $account->save();
    }

}
