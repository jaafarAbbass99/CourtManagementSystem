<?php

namespace App\Repository\Eloquent\User;

use App\Http\Resources\AccountUserResource;
use App\Http\Resources\ProfileUserResource;
use App\Models\Account;
use App\Models\User;
use App\Repository\Eloquent\BaseRepository;
use App\Repository\Eloquent\User\UserRepositoryInterface as UserUserRepositoryInterface;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Collection;


class UserRepository extends BaseRepository implements UserUserRepositoryInterface
{
    private $profile ;
    private Account $account ;
    private $token ;

    public function __construct(User $model)
    {
        parent::__construct($model);
    }
    
    public function createProfile(array $attributes):UserRepository
    {
         $this->profile = $this->model->create($attributes);
         return $this;      
    }
 
    public function withAccount(array $attributes):UserRepository 
    {
         $this->account = $this->profile->account()->create($attributes);
         $this->createTokenUser();
         return $this ;
    }

    public function createTokenUser()
    {
         $this->token = $this->account->createToken('auth_token')->plainTextToken;
    }

    public function createNewTokenUser()
    {     
          if($this->deleteTokenUser()){
               $this->createTokenUser();
          }
    }

    private function deleteTokenUser() : bool
    {     
          return $this->account->tokens()->delete();
    }


    public function sendEmail()
    {
          event(new Registered($this->account));
    }


    public function getToken():string
    {
          return $this->token;
    }

    public function getInfoUser():array
    {
         return [
               'account' => new AccountUserResource($this->account),
               'profile' => new ProfileUserResource($this->profile),
               'token'=> $this->token
         ];
    }
 
    public function all(): Collection
    {
        return $this->model->all();    
    }
}