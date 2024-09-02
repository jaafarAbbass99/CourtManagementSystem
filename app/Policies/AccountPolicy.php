<?php

namespace App\Policies;

use App\Models\Account;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountPolicy
{
    use HandlesAuthorization;

    public function canAddDocsForJoin(Account $user)
    {
        return true ;
        //return $this->isVerified($user);
        //return $this->isLawyer($user);
    }

    public function isVerified(Account $account)
    {
        return $account->hasVerifiedEmail();
    }


    public function isLawyer(User $user)
    {
        return $user->isLawyer();
    }

    public function isVerifiedLawyer(Account $account)
    {
        return $this->isLawyer($account->user) && $this->isVerified($account);
    }
}
