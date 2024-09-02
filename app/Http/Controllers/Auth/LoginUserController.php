<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\AccountUserResource;
use App\Http\Resources\ProfileUserResource;
use App\Repository\Eloquent\User\UserRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{
    
    
    public function store(LoginRequest $request): JsonResponse
    {

        $request->authenticate();

        $account = $request->user();
    
        $account->tokens()->delete();
        $token = $account->createToken('auth_token')->plainTextToken;
        
        $user = $account->user()->first();
        
        // if ($account->hasVerifiedEmail() && $user->role== 'User')
        // {
        //     $this->makeAccountIsApproved($account);
        // }

        $data = [
            'token' => $token,
            'profile' => new ProfileUserResource($user),
            'account' => new AccountUserResource($account),
        ];

        return $this->sendResponse($data, 'Login successful');  
    }

    private function makeAccountIsApproved($account)
    {
        $account->status = "Approved";
        $account->save();
    }


}
