<?php

namespace App\Http\Controllers\Judge\Add;

use App\Enums\SessionStatus;
use App\Enums\StatusCaseInSection;
use App\Http\Controllers\Controller;
use App\Http\Requests\Judge\AddRequests\AddDecisionRequest;
use App\Http\Requests\Judge\AddRequests\AddNextSessionRequest;
use App\Http\Resources\Judge\Show\DecisionCaseResource;
use App\Http\Resources\Judge\Show\SessionsCaseResource;
use App\Models\CaseJudge;
use App\Models\JudgeSection;
use App\Services\Judge\Add\AddDecisionService;
use App\Services\Judge\Add\CaseService as JudgeCaseService;
use App\Services\Judge\Add\SessionService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

use function PHPUnit\Framework\returnSelf;

class AddController extends Controller
{
    
    protected AddDecisionService $decisionService ;
    protected SessionService $sessionService ;
    protected JudgeCaseService $caseService ;

    public function __construct(AddDecisionService $decisionService ,SessionService $sessionService , JudgeCaseService $caseService)
    {
        $this->decisionService = $decisionService;
        $this->sessionService = $sessionService;
        $this->caseService = $caseService;
    }

    private function validateCaseStatus(int $caseId, ?int $sessionId = null): int
    {
        $caseJudgeId = $this->caseService->getIdCaseJudge($caseId);
        if (!$caseJudgeId) {
            throw new \Exception('لا تملك هذه القضية');
        }

        if (Gate::denies('isCaseOpen', [$caseJudgeId])) {
            throw new \Exception('القضية مفصولة');
        }

        if ($sessionId && Gate::denies('isSessionOpen', [$sessionId])) {
            throw new \Exception('الجلسة ليست مفتوحة');
        }

        return $caseJudgeId;
    }

    public function AddDecisionToCase(AddDecisionRequest $request)
    {
        try{

            $this->validateCaseStatus($request->case_id, $request->session_id);

            $account = Auth::user();
            $judgeSection = JudgeSection::where('user_id',$account->user->id)->first();
            $number_case = "_";
            if ($judgeSection){
                $number_case = CaseJudge::where('case_id',$request->case_id)
                                        ->where('judge_section_id' , $judgeSection->id)
                                        ->value('full_number');
            }

            $data = $this->decisionService->addDecisionWithDoc($request->all()
                +['number_case' => $number_case]
                +['user_name' => $account->user_name] 
                +['user_id' => $account->user->id]
                +['court_type_id' => $judgeSection->court_type_id ]
            );
            
            $result = DecisionCaseResource::make($data);
            return $this->sendResponse($result);
                
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function AddSessionToCase(AddNextSessionRequest $request)
    {
        try{
            $case_judge_id = $this->validateCaseStatus($request->case_id);

            $data = $this->sessionService->addSessionToCase(
                $request->all()+
                ['case_judge_id' => $case_judge_id]+
                ['session_status' => SessionStatus::scheduled->value]
            );
        
            $result = SessionsCaseResource::make($data);
            return $this->sendResponse($result);
    
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    
    // makeCaseSeen
    public function makeCaseSeen($case_id)
    {
        try{
            $this->caseService->setCaseSeen(Auth::user()->user->id,$case_id);
            return $this->sendOkResponse('تم تغيير الحالة الى مرئية');
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // makeCaseClose
    public function makeCaseClose($case_id)
    {
        try{
            $this->caseService->setCaseCloseOpen(Auth::user()->user->id,$case_id,StatusCaseInSection::CLOSE);
            return $this->sendOkResponse('تم فصل الدعوى');
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    // makeSessionCompleted
    public function makeSessionCompleted($session_id)
    {
        try{
            $this->sessionService->updateStatusSession($session_id , SessionStatus::completed);
            return $this->sendOkResponse('تم اغلاق الجلسة');
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    // makeSessionCancelled
    public function makeSessionCancelled($session_id)
    {
        try{
            $this->sessionService->updateStatusSession($session_id , SessionStatus::cancelled);
            return $this->sendOkResponse('تم الغاء الجلسة');
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    
}
