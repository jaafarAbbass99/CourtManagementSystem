<?php

namespace App\Http\Controllers\Auth;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Account;
use App\Models\User;
use App\Repository\Eloquent\User\UserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class RegisteredUserController extends Controller
{
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }
    

    public function store(RegisterRequest $request) : JsonResponse
    {
        $data = $this->userRepository->createProfile([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'birth_date' => $request->birth_date,
            'gender' => $request->gender,
            'role' => $request->role,
            'phone_number' => $request->phone_number,
            'photo' => $request->photo,
            'country' => $request->country , 
            'city' => $request->city , 
            'street' => $request->street
        ])
        ->withAccount([
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => Status::PENDING->value ,
        ])
        ->getInfoUser();

        //$this->userRepository->sendEmail();

        return $this->sendResponse($data,"register successful");



    }
}
