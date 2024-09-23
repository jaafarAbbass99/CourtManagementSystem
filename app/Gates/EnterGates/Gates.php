<?php

namespace App\Gates\EnterGates;

use App\Enums\Role;
use App\Enums\SessionStatus;
use App\Enums\Status;
use App\Enums\StatusCaseInSection;
use App\Models\Account;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\Decision;
use App\Models\DecisionOrder;
use App\Models\Interest;
use App\Models\LawyerCourt;
use App\Models\PowerOfAttorney;
use App\Models\RequiredIdeDoc;
use App\Models\Session;
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

        Gate::define('isUser', function (Account $account) {
            return $account->user->isUser();
        });

        Gate::define('verified_Lawyer_Judge', function () {
            return Gate::allows('isVerified') && (Gate::allows('isLawyer')||Gate::allows('isJudge') );
        });

        Gate::define('IsAccountApproved', function (Account $account) {
            return $account->isApproved();
        });

        Gate::define('isLawyerJoinedToCourt', function(Account $account , $court_id){
            return LawyerCourt::where('court_id',$court_id)
                        ->where('user_id',$account->user->id)->exists();
        });

        Gate::define('isCaseForLawyer',function(Account $account ,$case_id){
            $l_c_id = PowerOfAttorney::where('case_id', $case_id)->value('lawyerCourt_id');
                    
            return LawyerCourt::where('id', $l_c_id)
                        ->where('user_id',$account->user->id)->exists();
                        
        });

        Gate::define('isDateTimeValid', function(Account $account , $caseJudgeId ,$session_date ,$session_time){
            return Session::where('case_judge_id', $caseJudgeId)
            ->where('session_date', $session_date)
            ->where('session_time', $session_time)
            ->exists();
        });

        
        Gate::define('isCaseOpen', function(Account $account , $caseJudgeId){
            return CaseJudge::where('id',$caseJudgeId)
                        ->where('status',StatusCaseInSection::OPEN->value)
                        ->exists();
        });

        Gate::define('isSessionOpen', function(Account $account , $session_id){
            return Session::where('id',$session_id)
                        ->where('session_status',SessionStatus::scheduled->value)
                        ->exists();
                        
        });


        Gate::define('checkReqDoc',function(Account $account,$role,$req_doc){
            return RequiredIdeDoc::where('req_doc',$req_doc)
            ->where('for',$role)
            ->exists();
        });

        Gate::define('checkEndDecision',function(Account $account,$decision_id){
            return Decision::where('id',$decision_id)
                        ->where('status','نهائي')
                        ->exists();
        });


        // 'checkOrderForYou' , $order_id ,$user_id );

        Gate::define('checkOrderForYou',function(Account $account,$order_id){
            return DecisionOrder::where('id',$order_id)
                ->where('user_id',$account->user->id)
                ->exists();
        });

        Gate::define('isOrderPending',function(Account $account,$order_id){
            return DecisionOrder::where('id',$order_id)
                ->where('user_id',$account->user->id)
                ->where('status_order',Status::PENDING->value)
                ->exists();
        });


        Gate::define('isInterestMe',function(Account $account,$interest_id){
            return Interest::where('id',$interest_id)
                ->where('user_id',$account->user->id)
                ->exists();
        });

        Gate::define('isInterestesCase',function(Account $account,$case_id){
            return Interest::where('case_id',$case_id)
                ->where('user_id',$account->user->id)
                ->exists();
        });



    }
}