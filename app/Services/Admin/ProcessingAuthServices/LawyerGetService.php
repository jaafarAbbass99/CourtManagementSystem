<?php


namespace App\Services\Admin\ProcessingAuthServices;

use App\Enums\Role;
use App\Enums\Status;
use App\Http\Resources\AccountUserResource;
use App\Http\Resources\Admin\ProcessingAuth\GetLawyerWithRoleResource;
use App\Http\Resources\Docs\IdenDocResource;
use App\Http\Resources\ProfileUserResource;
use App\Models\Account;
use App\Models\Lawyer;
use App\Models\Document;
use App\Models\IdenDoc;
use Illuminate\Support\Facades\DB;

class LawyerGetService
{
    /**
     * اظهار الوثائق حسب طبيعتها لاجل محامي محدد
     * status (مقبولة - مرفوضة - معلقة)حالةالوثيقة
     * id رقم تعريف المحامي
     */
    public function getDocsForLawyer($id,Status $status)
    {
        $result = [];
        $data = IdenDoc::where('user_id', $id)
                    ->where('status', $status->value)
                    ->with(['file','type'])
                    ->get();
        if($data)    
            $restult = IdenDocResource::collection($data);
        return $restult ;
    }

    
    /**
     * اظهار المحاميين مع حالات حسابهم
     * status (مرفوض - مقيول - معلق)
     */
    public function getLawyers(Status $status)
    {
         $data = Account::where('status',$status->value)
            ->whereHas('user', function ($query) {
                $query->where('role', Role::LAWYER->value); 
            })
            ->with('user')
            ->get();
        return [
            "lawyer" => GetLawyerWithRoleResource::collection($data),
        ];   
    }

    public function getJudges(Status $status)
    {
         $data = Account::where('status',$status->value)
            ->whereHas('user', function ($query) {
                $query->where('role', Role::JUDGE->value); 
            })
            ->with('user')
            ->get();
        return [
            "Judge" => GetLawyerWithRoleResource::collection($data),
        ];   
    }


    /**
     * approves,rejectes Lawyer
     */
    public function approveAccount($AccountId)
    {
        $result = false;
        $account = Account::findOrFail($AccountId);
        
        if(!$account->isApproved() && !$account->isRejected())
            $result = $account->update(['status' => Status::APPROVED->value]);
        return $result;
    }

    public function rejectAccount($AccountId)
    {
        $result = false ;
        $account = Account::findOrFail($AccountId);
        if(!$account->isRejected())
            $result = $account->update(['status' => Status::REJECTED->value]);
        return $result;
    }
  
    
    /**
     * عدد المحامين حسب حالة  حسابهم 
     * status : (معلق - مرفوض - مقبول)
     */
    public function getLawyersCount(Status $status)
    {
        return Account::where('status',$status->value)
            ->whereHas('user', function ($query) {
                $query->where('role', Role::LAWYER->value); 
            })->count();
    }
    
}