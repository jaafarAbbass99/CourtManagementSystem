<?php

namespace App\Repository\Eloquent\User;

use App\Models\Account;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

interface UserRepositoryInterface
{
    public function createProfile(array $attributes):UserRepository;
    public function withAccount(array $attributes):UserRepository ;
    public function createTokenUser();
    public function sendEmail();
    public function createNewTokenUser();

    public function getToken():string ;
    public function getInfoUser() ;
    
    
    // public function createAccount(User $profile,array $attributes);
    //public function createToken(Account $account);

   //public function all(): Collection;
}