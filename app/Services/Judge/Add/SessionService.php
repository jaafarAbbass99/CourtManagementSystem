<?php 

namespace App\Services\Judge\Add;

use App\Enums\SessionStatus;
use App\Enums\TypeCaseDoc;
use App\Models\CaseJudge;
use App\Models\Decision;
use App\Models\JudgementDocs;
use App\Models\JudgeSection;
use App\Models\Session;
use App\Services\CaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate ;

class SessionService
{

    public function addSessionToCase($data)
    {   
        try{
            if (Gate::allows('isDateTimeValid',[$data['case_judge_id'], $data['session_date'], $data['session_time']])) {
                throw new \Exception('جلسة بنفس الخصائص موجودة بالفعل.');
            }
            return Session::create($data);
        }catch(Exception $e){
            throw new Exception(
                'مشكلة في اضافة جلسة: '.$e->getMessage());
        }
    }

    
    
    public function updateStatusSession($session_id , SessionStatus $status)
    {   
        try{
            return Session::where('id',$session_id)->update(['session_status'=>$status->value]);
        }catch(Exception $e){
            throw new Exception(
                'مشكلة في تغيير حالة الجلسة: '.$e->getMessage());
        }
    }









}
