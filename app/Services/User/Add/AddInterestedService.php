<?php 

namespace App\Services\User\Add;

use App\Models\Interest;
use Exception;
use Illuminate\Support\Facades\Auth;

class AddInterestedService
{

    public function AddInterested($case_id , $party)
    {   
        try{
            return Interest::create([
                'user_id' => Auth::user()->user->id,
                'case_id' => $case_id,
                'party' => $party,
            ]);
        }catch(Exception $e){
            throw new Exception('مشكلة في  الاهتمام بالدعوى'.$e->getMessage());
        }
    }

    public function cancelInterested($interest_id)
    {   
        try{
            return Interest::where('id',$interest_id)
                ->delete();
        }catch(Exception $e){
            throw new Exception('مشكلة في  الاهتمام بالدعوى'.$e->getMessage());
        }
    }



}