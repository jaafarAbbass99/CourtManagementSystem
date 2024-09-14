<?php 

namespace App\Services\Judge\Show;

use App\Models\CaseJudge;
use App\Models\CourtType;
use App\Models\JudgeSection;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowCasesService
{
    protected ShowSessionsCaseService $sessionService;


    public function __construct(ShowSessionsCaseService $sessionService)
    {
        $this->sessionService = $sessionService ;
    }

    private function getCasesForJudgeSection($judgeSection)
    {
        return $judgeSection->cases()
            ->with(['courtType:id,type','lawyers.user:id,first_name,last_name']);
    }


    public function getNewCases($user_id){
        $judgeSection = JudgeSection::where('user_id', $user_id)->first();
        
        if (!$judgeSection) {
            return response()->json(['message' => 'القاضي ليس لديه قسم قضائي محدد.'], 404);
        }

        return $this->getCasesForJudgeSection($judgeSection)
                    ->wherePivot('is_seen', false)
                    ->get();
    }


    public function getAllCases($user_id)
    {
        $judgeSection = JudgeSection::where('user_id', $user_id)->first();

        if (!$judgeSection) {
            return response()->json(['message' => 'القاضي ليس لديه قسم قضائي محدد.'], 404);
        }

        return $this->getCasesForJudgeSection($judgeSection)
            ->get();

    }

    public function getCase($user_id , $case_id)
    {
        $judgeSection = JudgeSection::where('user_id', $user_id)->first();

        if (!$judgeSection) {
            return response()->json(['message' => 'القاضي ليس لديه قسم قضائي محدد.'], 404);
        }

        return $this->getCasesForJudgeSection($judgeSection)
            ->where('cases.id',$case_id)
            ->get();

    }

    public function getSessionsCaseInPreviousCase($case_judge_id){
                                
        return $this->sessionService->getSessionsCase($case_judge_id);
        
    }


    public function getSessionsCase($user_id,$case_id){
        $judgeSection_id = JudgeSection::where('user_id', $user_id)->value('id');

        if (!$judgeSection_id) {
            return response()->json(['message' => 'القاضي ليس لديه قسم قضائي محدد.'], 404);
        }
        
        $case_judge_id = CaseJudge::where('judge_section_id',$judgeSection_id)
                                ->where('case_id',$case_id)
                                ->value('id');
                                
        return $this->sessionService->getSessionsCase($case_judge_id);
        
    }

    public function getUnAvailableDateTime($user_id){
        return DB::table('sessions')
        ->join('case_judges', 'sessions.case_judge_id', '=', 'case_judges.id')
        ->join('judge_sections', 'case_judges.judge_section_id', '=', 'judge_sections.id')
        ->where('judge_sections.user_id', $user_id)
        ->orderBy('sessions.session_date')
        ->orderBy('sessions.session_time')
        ->select('sessions.session_date', 'sessions.session_time')
        ->get();
    }
   
    public function getTodaySessions()
    {
        $today = Carbon::now()->format('Y-m-d'); 
        return $this->sessionService->getSessionsByDate($today);
    } 

    
    public function getMonthlySessions()
    {
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;   
        return $this->sessionService->getMonthlySessions($currentMonth,$currentYear);
    } 

    // getPreviousCourtToCase
    public function getPreviousCourtToCase($case_id)
    {
        $user_id = Auth::user()->user->id;

        $my_rank = JudgeSection::where('user_id', $user_id)
                    ->with(['courtType' => function ($query) {
                        $query->select('id', 'rank');
                }])
                ->get()
                ->pluck('courtType.rank');
        
        return DB::table('case_judges')
        ->join('judge_sections', 'case_judges.judge_section_id', '=', 'judge_sections.id')
        ->join('court_types', 'judge_sections.court_type_id', '=', 'court_types.id')
        ->join('users', 'judge_sections.user_id', '=', 'users.id')
        ->where('case_judges.case_id', $case_id)
        ->where('court_types.rank', '<', $my_rank)
        ->orderBy('case_judges.created_at','desc')
        ->select(
            'case_judges.id',           
            'case_judges.status',           
            'case_judges.date_close_case',           
            'users.first_name', 
            'users.last_name', 
            'court_types.rank',          
            'court_types.type'          
        )
        ->get();
              
    } 
    
}