<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Role;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AccountUserResource;
use App\Http\Resources\ProfileUserResource;


class LoginUserController extends Controller
{
    
    
    public function store(LoginRequest $request)
    {

        $request->authenticate();

        $account = $request->user();
    
        $account->tokens()->delete();
        $token = $account->createToken('auth_token')->plainTextToken;
        
        $user = $account->user;
        
        if(!$account->hasVerifiedEmail())
            return $this->sendError(' لا يمكنك تسجيل الدخول, تحقق من الايميل اولأ');
        
        if($user->role == Role::USER || $user->role == Role::ADMIN ){
            $this->makeAccountIsApproved($account);
        }

        $data = [
            'token' => $token,
            'profile' => new ProfileUserResource($user),
            'account' => new AccountUserResource($account),
        ];
        return $this->sendResponse($data, 'Login successful'); 
    }

    private function makeAccountIsApproved($account)
    {
        $account->status = Status::APPROVED->value;
        $account->save();
    }
    
}
