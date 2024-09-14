<?php 

namespace App\Services\Judge\Add;

use App\Enums\StatusCaseInSection;
use App\Models\CaseJudge;
use Exception;
use Illuminate\Support\Facades\Auth;

class CaseService
{    
    //getIdCaseJudge
    public function getIdCaseJudge($case_id)
    {   
        try{
            return CaseJudge::where('case_id',$case_id)
                    ->whereHas('judgeSection', function ($q){
                        $q->where('user_id',Auth::user()->user->id);
                    })->value('id');

        }catch(Exception $e){
            throw new Exception(
                'مشكلة في جلب id جدول CaseJudge: '.$e->getMessage());
        }
    }

    // setCaseSeen
    public function setCaseSeen($user_id,$case_id)
    {   
        try{
            CaseJudge::where('case_id',$case_id)
                ->whereHas('judgeSection',function ($q) use($user_id){
                    $q->where('user_id',$user_id);
                })->update(['is_seen' => 1]);

        }catch(Exception $e){
            throw new Exception(
                'مشكلة في تغيير حالة القضية: '.$e->getMessage());
        }
    }

    // setCaseClose
    public function setCaseCloseOpen($user_id,$case_id,StatusCaseInSection $status)
    {   
        try{
            CaseJudge::where('case_id',$case_id)
                ->whereHas('judgeSection',function ($q) use($user_id){
                    $q->where('user_id',$user_id);
                })->update(['status' => $status->value]);

        }catch(Exception $e){
            throw new Exception(
                'مشكلة في تغيير حالة القضية: '.$e->getMessage());
        }
    }

}
