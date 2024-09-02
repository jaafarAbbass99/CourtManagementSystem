<?php

namespace App\Gates\EnterGates;

use App\Enums\Role;
use App\Enums\Status;
use App\Models\Account;
use App\Models\LawyerCourt;
use App\Models\PowerOfAttorney;
use App\Models\RequiredIdeDoc;
use Illuminate\Support\Facades\Gate;

class Gates
{
    
    public static function boot()
    {
        Gate::define('isVerified', function (Account $account) {
            return $account->hasVerifiedEmail();
        });

        Gate::define('isLawyer', function (Account $account) {
            return $account->user->isLawyer();
        });

        Gate::define('isJudge', function (Account $account) {
            return $account->user->isJudge();
        });


        Gate::define('isAdmin', function (Account $account) {
            return $account->user->isAdmin() ;
        });

        Gate::define('verified_Lawyer_Judge', function () {
            return Gate::allows('isVerified') && (Gate::allows('isLawyer')||Gate::allows('isJudge') );
        });

        Gate::define('IsAccountApproved', function (Account $account) {
            return $account->isApproved();
        });

        Gate::define('isLawyerJoinedToCourt', function(Account $account , $court_id){
            return LawyerCourt::where('id',$court_id)
                        ->where('user_id',$account->user->id)->exists();
        });

        Gate::define('isCaseForLawyer',function(Account $account ,$case_id){
            $l_c_id = PowerOfAttorney::where('case_id', $case_id)->value('lawyerCourt_id');
            
            return LawyerCourt::where('id', $l_c_id)
                        ->where('user_id',$account->user->id())->exists();
                        
        });


        // Gate::define('checkReqDoc',function(Account $account,$role,$req_doc){
        //     //return true ;
        //     $res = RequiredIdeDoc::where('req_doc',$req_doc)
        //     ->where('for',$role)
        //     ->exists();
        //     if($res)
        //         return true;
        //     return false ;
        // });


    }
}