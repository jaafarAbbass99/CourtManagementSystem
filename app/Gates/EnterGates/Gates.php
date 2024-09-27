<?php

namespace App\Gates\EnterGates;

use App\Enums\Party;
use App\Enums\Role;
use App\Enums\SessionStatus;
use App\Enums\Status;
use App\Enums\StatusCaseInSection;
use App\Models\Account;
use App\Models\AttorneyOrders;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\Decision;
use App\Models\DecisionOrder;
use App\Models\defenseOrder;
use App\Models\Dewan;
use App\Models\Interest;
use App\Models\LawyerCourt;
use App\Models\order;
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

        Gate::define('isEmployee', function (Account $account) {
            return $account->user->isEmployee();
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

        Gate::define('isIRepresentForCase',function(Account $account,$case_id,Party $party){
            return Interest::where('case_id',$case_id)
                ->where('user_id',$account->user->id)
                ->where('party',$party->value)
                ->exists();
        });

        Gate::define('isAbleAttorneys',function(Account $account,$case_id){
            return Interest::where('case_id',$case_id)
                ->where('user_id',$account->user->id)
                ->where('is_admin',1)
                ->exists();
        });

        Gate::define('isInterestAsPartyTwo',function(Account $account,$case_id){
            return Interest::where('case_id',$case_id)
                ->where('user_id',$account->user->id)
                ->where('party',Party::PARTY_TWO->value)
                ->exists();
        });

        Gate::define('isInterestAsPartyOne',function(Account $account,$case_id){
            return Interest::where('case_id',$case_id)
                ->where('user_id',$account->user->id)
                ->where('party',Party::PARTY_ONE->value)
                ->exists();
        });
        //26 
        Gate::define('isDefenseOrderForMe',function(Account $account, $order_id){
            return  defenseOrder::whereHas('order',function ($q)use($order_id){
                        $q->where('id',$order_id);
                    })->whereHas('interest',function ($q) use($account){
                        $q->where('user_id',$account->user->id)
                        ->where('is_admin',true);
                    })->exists();
        });


        Gate::define('isAttorneyOrderForMe',function(Account $account, $order_id){
            
            return AttorneyOrders::where('user_id',$account->user->id)
                    ->whereHas('order',function ($q)use($order_id){
                        $q->where('id',$order_id);
                    })
                    ->exists();
        });

        Gate::define('isAttorneyOrderForMeAndAbleCancel',function(Account $account, $order_id){
            return AttorneyOrders::where('user_id',$account->user->id)
                    ->whereHas('order',function ($q)use($order_id){
                        $q->where('id',$order_id)
                        ->where('status_order',Status::PENDING->value);
                    })
                    ->exists();
        });

        
        Gate::define('isDefenseOrderForMeAndAbleCancel',function(Account $account, $order_id){
            return  defenseOrder::whereHas('order',function ($q)use($order_id){
                $q->where('id',$order_id)
                ->where('status_order',Status::PENDING->value);
            })->whereHas('interest',function ($q) use($account){
                $q->where('user_id',$account->user->id)
                ->where('is_admin',true);
            })->exists();
        });

        Gate::define('isDefenseExest',function(Account $account, $lowyer_court_id , $case_id){
            return PowerOfAttorney::where('case_id',$case_id)
                    ->where('lawyerCourt_id',$lowyer_court_id)
                    ->exists();
        });

        Gate::define('isAttorneyExest',function(Account $account, $order_id){
            return PowerOfAttorney::where('order_id',$order_id)
                    ->exists();
        });
        
        Gate::define('isAbleCancelOrder',function(Account $account, $order_id){
            return order::where('id',$order_id)
                ->where('status_order',Status::PENDING->value)
                ->exists();
        });

        Gate::define('isLawyerTakeCase',function(Account $account,$case_id,$lawyer_id){
            return PowerOfAttorney::where('case_id',$case_id)
                    ->whereHas('lawyerInCourt',function ($q) use($lawyer_id){
                        $q->where('user_id',$lawyer_id);
                    })
                    ->exists();
        });

        
        Gate::define('isAbleOkOrder',function(Account $account, $order_id){
            return order::where('id',$order_id)
                ->where('status_order',Status::APPROVED->value)
                ->exists();
        });

        // IsEmployeeInCourtCase
        Gate::define('IsEmployeeInCourtCase',function(Account $account, $interest_id){
            $dewan = Dewan::where('user_id',$account->user->id)->first();
            return Interest::whereHas('case.now',function ($q)use($dewan){
                        $q->where('court_type_id',$dewan->court_type_id);
                })->where('id',$interest_id)
            ->exists();
        });
    }
}