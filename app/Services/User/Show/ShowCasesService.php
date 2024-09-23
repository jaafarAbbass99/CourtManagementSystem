<?php 

namespace App\Services\User\Show;

use App\Models\CaseJudge;
use App\Models\Cases;
use App\Models\CourtType;
use App\Models\JudgeSection;
use App\Models\Session;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ShowCasesService
{

    public function getCases($data){

        $year = $data['case_year'];
        $case_number = $data['case_type'].'-'.$data['case_number'];
        $full_number_for_case = $case_number . '-' . substr($year, -2) . '-' . $data['case_base_number']; 
        $court_type_id = $data['case_court_type_id'];
        

        return Cases::where('full_number', $case_number)
            ->whereYear('created_at', $data['case_year'])
            ->whereHas('case_judges', function ($q) use ($full_number_for_case, $court_type_id) {

                $q->where('full_number', $full_number_for_case)
                ->whereHas('judgeSection', function ($qq) use ($court_type_id) {
                    $qq->where('court_type_id', $court_type_id);

                });
            })->first();
    }

    public function getPreviousCourtToCase($case_id)
    {
        $user_id = Auth::user()->user->id;
        
        return DB::table('case_judges')
        ->join('judge_sections', 'case_judges.judge_section_id', '=', 'judge_sections.id')
        ->join('court_types', 'judge_sections.court_type_id', '=', 'court_types.id')
        ->join('users', 'judge_sections.user_id', '=', 'users.id')
        ->where('case_judges.case_id', $case_id)
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