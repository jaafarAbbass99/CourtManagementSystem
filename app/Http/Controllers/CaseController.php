<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lawyer\ShowCasesByStatusRequest;
use App\Http\Requests\OpenCaseByLawyerRequest;
use App\Http\Requests\ShowCaseByDetailsRequest;
use App\Http\Resources\Cases\CaseResources;
use App\Http\Resources\Cases\CasesResources;
use App\Services\CaseService;
use Exception;

class CaseController extends Controller
{
    protected $caseService;

    public function __construct(CaseService $caseService)
    {
        $this->caseService = $caseService;
    }

    // فتح دعوى جديدة
    public function openCase(OpenCaseByLawyerRequest $request)
    {
        
        $result = $this->caseService->openCase($request->all());
        
        if($result)
            return $this->sendOKResponse('تم فتح الدعوى بنجاح.') ;
        return $this->sendError('لم يتم رفع الدعوى , حاول من جديد');
    }

    // عرض الدعاوى التابعة له في محكمة محددة
    public function showCasesInCourt($court_id)
    {
        $cases = $this->caseService->getCasesInCourt($court_id);
        
        $result =  CasesResources::collection($cases);
        
        return $this->sendResponse($result);
    }

    // عرض الدعاوى التابعة له في كل المحاكم
    public function showAllCases()
    {
        $cases = $this->caseService->getAllCases(auth()->user()->user->id);
        $result =  CasesResources::collection($cases);
        return $this->sendResponse($result,'جميع الدعاوى في كل محاكمي');
    }

    // عرض دعوى معينة حسب اسم المدعي والعام والمحكمة
    public function showCaseByDetails(ShowCaseByDetailsRequest $request)
    {
        $case = $this->caseService->getCaseByDetails($request->party_one, $request->my_court_id, $request->year);
        if (!$case) {
            return response()->json(['message' => 'لم يتم العثور على الدعوى.'], 404);
        }
        $result =  CasesResources::collection($case);

        return $this->sendResponse($result);
    }

    // عرض دعوى حسب رقمها
    public function showCaseByNumber($number_case)
    {
        $case = $this->caseService->getCaseByNumber($number_case);

        if (!$case) {
            return response()->json(['message' => 'لم يتم العثور على الدعوى.'], 404);
        }
        $result =  CasesResources::collection($case);

        return response()->json($result);
    }

    public function showCasesInCourtByStatus(ShowCasesByStatusRequest $request)
    {
        try{
            $data = $this->caseService->getCasesInCourtByStatus($request->only('my_court_id','status'));

            if($data->isEmpty())
                return $this->sendOkResponse('لايوجد نتائج لعرضها');

            $result = CasesResources::collection($data);
            return $this->sendResponse($result,'كل الدعاوى ');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showCountCasesInCourtByStatus(ShowCasesByStatusRequest $request)
    {
        try{
            $data = $this->caseService->getCountCasesInCourtByStatus($request->only('my_court_id','status'));

            return $this->sendResponse(['count'=>$data],'عدد الدعاوى');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

}
