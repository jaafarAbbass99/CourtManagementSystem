<?php 

namespace App\Services\Judge\Add;

use App\Enums\SessionStatus;
use App\Enums\StatusCaseInSection;
use App\Enums\TypeCaseDoc;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\Decision;
use App\Models\JudgementDocs;
use App\Models\JudgeSection;
use App\Models\Session;
use App\Models\Win;
use App\Services\CaseService;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate ;

class AddDecisionService
{

    protected CaseService $caseService ;

    public function __construct(CaseService $caseService)
    {
        $this->caseService = $caseService;
    }
    
    public function getSectionJudge($id){
        try{
            $JudgeSection = JudgeSection::where('user_id',$id)->first();
            if($JudgeSection)
                return $JudgeSection ;
            return null ;
        }catch(Exception $e){

        }
           
    }

    public function addDecision($data)
    {   
        try{
            return Decision::create($data);
        }catch(Exception $e){
            throw new Exception('مشكلة في اضافة القرار'.$e->getMessage());
        }
    }

    public function addJudgementDoc($data)
    {   
        try{
            return JudgementDocs::create($data);
        }catch(Exception $e){
            throw new Exception('مشكلة في اضافة الملف'.$e->getMessage());
        }
    }
    public function getAttorneys($case_id , $for){
        return Win::whereHas('attorney',function ($q) use($case_id,$for){
            $q->where('case_id',$case_id)
               ->where('representing',$for);
            })->whereHas('courtType.judgeSections', function ($q){
                $q->where('user_id', Auth::user()->user->id);
            })->first();
    }

    public function makeDecisionWinFor($case_id , $for){
        $result = $this->getAttorneys($case_id , $for);
        if($result){
            $result->get = 'yes';
            $result->save();
        }
    }
    public function makeDecisionLoseFor($case_id){
        Win::whereHas('attorney',function ($q) use($case_id){
            $q->where('case_id',$case_id);
            })->whereHas('courtType.judgeSections', function ($q){
                $q->where('user_id', Auth::user()->user->id);
            })->where('get','yet')->update(['get'=>'no']);
    }

    public function addDecisionWithDoc($data)
    {   
        DB::beginTransaction();
        try{
            if($data['status'] == 'نهائي' ){
                $this->makeDecisionWinFor($data['case_id'],$data['favor']);
                
                $this->makeDecisionLoseFor($data['case_id']);
            }
            
            $decision = $this->addDecision($data);

            $case_doc = $this->caseService->uploadeCaseDocs([
                'file' => $data['file'],
                'summary'=> $data['summary_file'],
                'file_type' => TypeCaseDoc::HOKM->value,
                'court_type_id' => $data['court_type_id'],
                'case_id' => $data['case_id'],
                'user_id' => $data['user_id'],//الشخص يلي رفع الملف (judge)
                'user_name' => $data['user_name'],
                'number_case'=> $data['number_case'] ,
            ]);

            $this->addJudgementDoc([
                'decision_id' => $decision->id, 
                'case_doc_id' => $case_doc->id,
            ]);
            DB::commit();
            return $decision ;
        }catch(Exception $e){
            DB::rollBack();
            throw new Exception('مشكلة في اضافة الملف'.$e->getMessage());
        }
    }


}
