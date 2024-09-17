<?php

namespace App\Services;

use App\Enums\Representing;
use App\Enums\SessionStatus;
use App\Enums\SessionType;
use App\Enums\StatusCaseInSection;
use App\Enums\TypeCaseDoc;
use App\Enums\TypeCourt;
use App\Models\CaseDoc;
use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\Court;
use App\Models\CourtType;
use App\Models\Document;
use App\Models\JudgeSection;
use App\Models\LawyerCourt;
use App\Models\PowerOfAttorney;
use App\Models\Session;
use App\Models\Win;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CaseService
{
    protected $s_time;
    protected $e_time;
    protected $date ;

    public function __construct()
    {
        $this->s_time = '9:00' ;
        $this->e_time = '17:00:00' ;
    }
    // فتح دعوى جديدة
    public function openCase($data) 
    {
        DB::beginTransaction();

        try{
            $typeId = Court::whereHas('courtTypes',function ($q){
                $q->where('type_form',TypeCourt::ST->value);
            })->where('id',$data['court_id'])->value('id');
            
            $sectionId  = $this->getSectionByRandom($typeId);
            
            $case = Cases::create([
                'party_one' => $data['party_one'],
                'party_two' => $data['party_two'],
                'subject' => $data['subject'],
                'court_type_id' => $typeId ,
                'exist_now' => $sectionId ,
                'case_type_id' => $data['case_type_id'],
            ]);

            $attorney = PowerOfAttorney::create([
                'case_id' => $case->id,
                'lawyerCourt_id' => $data['my_court_id'],
                'representing' => Representing::PARTY_ONE->value ,  
            ]);

            Win::create([
                'court_type_id' => $typeId ,
                'attorney_id' =>$attorney->id ,
                'get' => 'yet',
            ]);



            $caseJudge = CaseJudge::create([
                        'case_id' => $case->id ,
                        'judge_section_id' => $sectionId ,
                        'status' => StatusCaseInSection::OPEN->value,
                    ]);

            $account = Auth::user() ;

            
            $this->uploadeCaseDocs([
                'file' => $data['file'],
                'summary'=>$data['summary'],
                'file_type' => TypeCaseDoc::DAWA->value,
                'court_type_id' => $typeId ,
                'case_id' => $case->id,
                'user_id' => $account->user->id,//الشخص يلي رفع الملف (المحامي)
                'user_name' => $account->user_name,
                'number_case'=>$caseJudge->full_number,
            ]);
            $session = $this->openSession($sectionId, $caseJudge->id);

            DB::commit();
            return $session;

        }catch(Exception $e){
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

    }

    public function openSession($sectionId,$case_judge_id){
        //عند القاضي المستلم جلب اخر وقت بعد اسبوع من رفع الحلسة
        try{
            $date  = Carbon::now()->addWeek();
            
            while ($date->isFriday() || $date->isSaturday()) {
                $date->addDay();
            }
            
            $time = Session::where('session_date',$date->format('Y-m-d'))
                ->orderBy('session_time','desc')
                ->whereHas('caseJudge',function ($q) use($sectionId){
                    $q->where('judge_section_id',$sectionId);
                })->value('session_time');
            
                            
            while ($time == $this->e_time){
                $date->addDay();
                
                $time = Session::where('session_date',$date->format('Y-m-d'))
                ->orderBy('session_time','desc')
                ->whereHas('caseJudge',function ($q) use($sectionId){
                    $q->where('judge_section_id',$sectionId);
                })->value('session_time');
                
            }
            if(!$time)
                $time = $this->s_time;
                
            $session_time = Carbon::parse($time)->addHour();
            return Session::create([
                    'case_judge_id'=> $case_judge_id,
                    'session_date' => $date->format('Y-m-d'),
                    'session_time' => $session_time->format('H:i:s'),
                    'session_type' => SessionType::PRELIMINARY->value ,
                    'session_status' => SessionStatus::scheduled->value,
                ]);
            
        }catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    } 

    public function addFilesToCase($request){
        
        DB::beginTransaction();

        try{
            
            $account = Auth::user() ;

            $case_id = $request->case_id ;
            $court_type_id = $request->court_type_id ;
            $case_judge_id = $request->case_judge_id;
            
            $full_number = CaseJudge::where('id',$case_judge_id)->value('full_number');


            foreach ($request->file('files') as $index => $file) {
                
                $this->uploadeCaseDocs([
                    'file' => $file ,
                    'summary'=>$request->summaries[$index],
                    'file_type' => $request->type_docs ?? TypeCaseDoc::DAWA->value,
                    'court_type_id' => $court_type_id,
                    'case_id' => $case_id ,
                    'user_id' => $account->user->id,//الشخص يلي رفع الملف (المحامي)
                    'user_name' => $account->user_name,
                    'number_case'=>$full_number,
                ]);
            }
    
            DB::commit(); 
            return true;

        }catch(Exception $e){
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
    }


    // عرض الدعاوى التابعة له في محكمة محددة
    public function getCasesInCourt($courtId)
    {
        return PowerOfAttorney::whereHas('lawyerInCourt' , function ($q)use($courtId){
            $q->where('court_id', $courtId);
        })
        ->with('case')
        ->get();
    }

    // عرض الدعاوى التابعة له في كل المحاكم
    public function getAllCases($lawyerId)
    {
        $lawyerCourts = LawyerCourt::where('user_id',$lawyerId)
                    ->pluck('id');
        return PowerOfAttorney::whereIn('lawyerCourt_id',$lawyerCourts)
                        ->with('case')->get();
    }
    
    // عرض دعوى واحدة في محكمة واحدة 
    public function getCaseInCourt($caseId){

        return Cases::with(['powerOfAttorneys','caseTypes'])
                ->where('id',$caseId)->first();
    }

    // بحث عن دعوى معينة حسب تفاصيل محددة
    public function getCaseByDetails($partyOne, $courtId, $year)
    {
        return PowerOfAttorney::whereHas('lawyerInCourt' , function ($q)use($courtId){
            $q->where('court_id', $courtId);
        })
        ->whereHas('case',function($q) use($partyOne,$year){
            $q->where('party_one', $partyOne)
            ->whereYear('created_at', $year);  
        })
        ->with('case.caseType')
        ->get();
        
    }


    public function getCaseByNumber($number){
        return PowerOfAttorney::whereHas('case',function($q) use($number){
                    $q->where('full_number',$number);
                })
                ->with(['case.caseType' , 'case.now'])
                ->first();
    }

    // عرض دعاوى مكتسبة| غير مكتسبة  في محكمة (نقض - استئناف-..)   
    public function getCasesInCourtByStatus($data){
        // $court_id = LawyerCourt::where('id',$data['my_court_id'])->value('court_id');
        
        $TypeCourtId = CourtType::where([
            ['court_id', $data['court_id']],
            ['type_form', $data['type_court']],
        ])->value('id');
        
        $powerOfAttorneys = PowerOfAttorney::with('case')
            ->whereHas('win', function ($query) use ($data, $TypeCourtId) {
                $query->where([
                    ['get', $data['status']],
                    ['court_type_id', $TypeCourtId],
                ]);
            })
            ->get();
        return $powerOfAttorneys ;
    }

    public function getCountCasesInCourtByStatus($data){
        return Win::where('get',$data['status'])
            ->whereHas('courtType', function ($q) use ($data){
                $q->where([
                    ['court_id',$data['my_court_id']],
                    ['type_form',$data['type_court']],
                ]);
            })->count();
    }

    public function getDetailsCase($data){
        $judgeDetails = CaseJudge::with([
            'judgeSection.judge' => function ($query) {
                $query->select('id', 'first_name', 'last_name');
            },
            'judgeSection.section'
        ])
        ->where('case_id', $data['case_id'])
        ->whereHas('judgeSection', function ($query) use ($data) {
            $query->where('court_type_id', $data['court_type_id']);
        })
        ->latest('updated_at')
        ->first();
    
        return $judgeDetails;
    }

    // ارجاع الدعاوى المفصولة | غير المفصولة التابعة للمحامي + تفاصيلها عند كل القضاة
    public function getCasesByStatusInSection($status_case,$lawyerId){
        try{
            $lawyerCourt = LawyerCourt::where('user_id', $lawyerId)->first();
            
            if ($lawyerCourt) {
                $cases = $lawyerCourt->cases()
                    ->whereHas('judgeSections', function ($query) use($status_case) {
                        $query->where('case_judges.status', $status_case);
                    })
                    ->with(['judgeSections' => function ($query){
                        $query->latest('case_judges.updated_at')
                              ->with('judge:id,first_name,last_name'  , 'courtType');
                    }])
                    ->get();
                    return $cases;
            }
            return [];
        }catch(Exception $e){
            return $e;
        }
    }

    private function getSectionByRandom($typeId){
        $sectionIds = JudgeSection::where('court_type_id',$typeId)->pluck('id');
        
        if ($sectionIds->isNotEmpty())
            $randomId = Arr::random($sectionIds->toArray());

        return $randomId ;    

    }


    public function getStatisticsCasesByCourtTypeAndStatus($lawyerId)
    {
        try {
            $cases = CaseJudge::select(
                'court_types.type as court_type',
                'courts.name as court_name', 
                'courts.province as court_province', 
                'case_judges.status', 
                DB::raw('COUNT(case_judges.id) as case_count')
            )
            ->join('judge_sections', 'case_judges.judge_section_id', '=', 'judge_sections.id') 
            ->join('court_types', 'judge_sections.court_type_id', '=', 'court_types.id') 
            ->join('courts', 'court_types.court_id', '=', 'courts.id') 
            ->join('cases', 'case_judges.case_id', '=', 'cases.id')
            ->join('power_of_attorneys', 'power_of_attorneys.case_id', '=', 'cases.id') 
            ->join('lawyer_courts', 'power_of_attorneys.lawyerCourt_id', '=', 'lawyer_courts.id') 
            ->where('lawyer_courts.user_id', $lawyerId) 
            ->whereIn('case_judges.status', ['open', 'close']) 
            ->groupBy('court_types.type', 'courts.name', 'courts.province', 'case_judges.status')
            ->orderBy('courts.name') 
            ->orderBy('court_types.type') 
            ->orderBy('case_judges.status') 
            ->get();
    
            return $cases ;

        } catch (Exception $e) {
            throw new Exception('Error fetching case data from the database.');
        }
    }
    


    // رفع ملفات القضية 
    public function uploadeCaseDocs($data){

        $file = $data['file'];

        $fileName = $this->getFileName($file);
        $filePath = "cases/".$data['number_case']."/"."case_docs/".$data['user_id']."_".$data['user_name'] ."/".$data['file_type'];
         
        try{

            $doc = $this->AddFile($fileName,$filePath);
            
            if($doc){
                
                $case_doc = CaseDoc::create([
                    'summary' => $data['summary'],
                    'type' => $data['file_type'],
                    'court_type_id' => $data['court_type_id'],
                    'case_id' =>$data['case_id'],
                    'doc_id' =>$doc->id,
                    'user_id' => $data['user_id'],
                ]);

                if($case_doc){
                    $file->storeAs($filePath, $fileName,'public');
                    return $case_doc ;
                }
                
            }

        }catch(Exception $e){
            throw new Exception("خطأ في رفع الملفات" . $e->getMessage());
        }

    }


    private function getFileName($file)
    {
        return  now()->format('Ymd_His').'_' .$file->getClientOriginalName();
    }

    public function AddFile($file_name , $file_path){
        try{
            $doc = Document::create([
                'doc_name' => $file_name,
                'document_path' => $file_path . "/" ,
            ]);
            return $doc ;
        }
        catch(Exception $e){
            throw new Exception($e->getMessage());
        }
    }

     /**
     * عرض جلسات الدعوى 
     * case_judge_id هي رقم الدعوى عند القاضي الذي استلم القضية
     */
    public function getSessionsCase($case_id,$case_judge_id){
        return Session::whereHas('caseJudge', function ($q) use ($case_id,$case_judge_id) {
            $q->where('id', $case_judge_id)
                ->where('case_id',$case_id);
        })
        ->with(['decision' => function ($query) {
            $query->with([
                'caseDocs' => function ($subQuery) {
                    $subQuery->select('case_docs.id', 'case_docs.summary', 'case_docs.type', 'case_docs.doc_id')
                             ->with(['file' => function ($fileQuery) {
                                 $fileQuery->select('documents.id', 'documents.doc_name', 'documents.document_path');
                             }]);
                }
            ]);
        }])
        ->get();
    }




}
