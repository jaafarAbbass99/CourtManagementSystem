<?php

namespace App\Services;

use App\Enums\Representing;
use App\Models\Cases;
use App\Models\Court;
use App\Models\LawyerCourt;
use App\Models\PowerOfAttorney;
use Exception;

class CaseService
{
    // فتح دعوى جديدة
    public function openCase($data) : bool
    {
        try{
            $court_id = LawyerCourt::where('id',$data['my_court_id'])->value('court_id');
    
            $case = Cases::create([
                'party_one' => $data['party_one'],
                'party_two' => $data['party_two'],
                'subject' => $data['subject'],
                'court_id' => $court_id,
                'case_type_id' => $data['case_type_id'],
            ]);
    
            PowerOfAttorney::create([
                'case_id' => $case->id,
                'lawyerCourt_id' => $data['my_court_id'],
                'representing' => Representing::PARTY_ONE->value ,
                'get' => 'yet',
            ]);

            return true;

        }catch(Exception $e){
            return false ;
        }

        // لا ننسى توزيع الدعوى على احد الاقسام في المحكمة بشكل الي

    }

    // عرض الدعاوى التابعة له في محكمة محددة
    public function getCasesInCourt($courtId)
    {
        return PowerOfAttorney::where('lawyerCourt_id', $courtId)
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
        return PowerOfAttorney::where('id',$courtId)
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
                ->with('case.caseType')
                ->get();

    }

    public function getCasesInCourtByStatus($data){
        return PowerOfAttorney::where('lawyerCourt_id', $data['my_court_id'])
                ->where('get',$data['status'])
                ->with('case')
                ->get();
    }

    public function getCountCasesInCourtByStatus($data){
        return PowerOfAttorney::where('lawyerCourt_id', $data['my_court_id'])
                ->where('get',$data['status'])
                ->count();
                
    }

}
