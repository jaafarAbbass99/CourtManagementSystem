<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddFilesToCaseRequest;
use App\Http\Requests\CaseJudgeIdRequest;
use App\Http\Requests\Lawyer\ShowCasesByStatusRequest;
use App\Http\Requests\Lawyer\ShowDetailsCaseRequest;
use App\Http\Requests\OpenCaseByLawyerRequest;
use App\Http\Requests\ShowCaseByDetailsRequest;
use App\Http\Requests\StatusCaseCloseOpenRequest;
use App\Http\Resources\Cases\CasesResources;
use App\Http\Resources\Judge\Show\SessionsCaseResource;
use App\Http\Resources\Lawyer\CaseInSectionResource as LawyerCaseInSectionResource;
use App\Http\Resources\Lawyer\SessionWithSectionResource;
use App\Http\Resources\Lawyer\ShowDetailsCaseResource;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Services\CaseService;
use Exception;
use Illuminate\Support\Facades\Auth;

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
        try{
            
            $data = $this->caseService->openCase($request->all());
            return $data ;
            $result = SessionWithSectionResource::make($data);
            if($data)
                return $this->sendResponse($result,'تم فتح الدعوى بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في فتح دعوى'
            );
        }
    }

    // addFilesToCase
    public function addFilesToCase(AddFilesToCaseRequest $request)
    {
        try{
            $data = $this->caseService->addFilesToCase($request);
            if($data)
                return $this->sendOKResponse('تم اضافة الملفات بنجاح.') ;
            
        }catch(Exception $e){
            return $this->sendErrorWithCause(
                $e->getMessage(),'خطأ في اضافة الملفات'
            );
        }
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
        $case = $this->caseService->getCaseByDetails($request->party_one, $request->court_id, $request->year);
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
        $result =  CasesResources::make($case);

        return $this->sendResponse($result , 'الدعوى');
    }

    public function showCasesInCourtByStatus(ShowCasesByStatusRequest $request)
    {
        try{

            $data = $this->caseService->getCasesInCourtByStatus($request->only('court_id','type_court','status'));

            if($data->isEmpty())
                return $this->sendOkResponse('لايوجد نتائج لعرضها');

            $result = CasesResources::collection($data);
            return $this->sendResponse($result,'كل الدعاوى');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showCountCasesInCourtByStatus(ShowCasesByStatusRequest $request)
    {
        try{
            $data = $this->caseService->getCountCasesInCourtByStatus($request->only('my_court_id','type_court','status'));

            return $this->sendResponse(['count'=>$data],'عدد الدعاوى');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // اظهار القضية في اي قسم وعند اي محامي والجلسات
    public function showDetailsCase(ShowDetailsCaseRequest $request){
        try{
            $data = $this->caseService->getDetailsCase($request->only('case_id','court_type_id'));

            if(!$data)
                return $this->sendOkResponse('لايوجد نتائج لعرضها');
            // return $data ;
            $result = ShowDetailsCaseResource::make($data);
            return $this->sendResponse($result,'تفاصيل  الدعوى  ');
            
            $result = LawyerCaseInSectionResource::make($data);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    public function showCasesCloseOrOpenWithDetails($status_case){
        try{

            $data = $this->caseService->getCasesByStatusInSection($status_case, Auth::user()->user->id);

            if($data->isEmpty())
                return $this->sendOkResponse('لايوجد نتائج لعرضها');

            $result = LawyerCaseInSectionResource::collection($data);
            return $this->sendResponse($result,'الدعاوى حسب حالتها ضمن القسم  ');
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showSessionsCase($case_id , $case_judge_id )
    {
        try{

            $data = $this->caseService->getSessionsCase($case_id,$case_judge_id);

            $result = SessionsCaseResource::collection($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    public function getCasesSummary()
    {
        try {
            $data = $this->caseService->getStatisticsCasesByCourtTypeAndStatus(Auth::user()->user->id);

            if ($data->isEmpty()) {
                return $this->sendOkResponse('لايوجد نتائج لعرضها');
            }
            
            return $this->sendResponse($data,'نتائج احصائية ');

            return $cases;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }




}
