<?php 

namespace App\Services\Judge\Show;

use App\Enums\StatusCaseInSection;
use App\Models\CaseJudge;
use App\Models\JudgeSection;
use App\Models\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowSessionsCaseService
{
    
    /**
     * عرض جلسات الدعوى 
     * case_judge_id هي رقم الدعوى عند القاضي الذي استلم القضية
     */
    public function getSessionsCase($case_judge_id){
        return Session::whereHas('caseJudge', function ($q) use ($case_judge_id) {
            $q->where('id', $case_judge_id);
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

    // getTodaySessions
    public function getSessionsByDate($date)
    {
        $userId = Auth::user()->user->id;
        return $this->getSessions()
            ->where('judge_sections.user_id', $userId)
            ->where('sessions.session_date',$date)
            ->get();
    } 

    public function getMonthlySessions($month , $year)
    {
        $userId = Auth::user()->user->id;

        return $this->getSessions()
            ->where('status','!=',StatusCaseInSection::CLOSE->value)
            ->where('judge_sections.user_id', $userId)
            ->whereMonth('sessions.session_date', $month)
            ->whereYear('sessions.session_date', $year)
            ->get();
    } 

    private function getSessions(){
        return Session::join('case_judges', 'sessions.case_judge_id', '=', 'case_judges.id')
            ->join('judge_sections', 'case_judges.judge_section_id', '=', 'judge_sections.id')
            ->orderBy('sessions.session_date')
            ->orderBy('sessions.session_time')
            ->select(
                'sessions.*',
                'case_judges.case_id',
                'case_judges.full_number',
                'case_judges.status',
            );
    }


}