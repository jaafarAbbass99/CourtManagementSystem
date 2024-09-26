<?php

namespace App\Http\Controllers\User\Add;


use App\Http\Controllers\Controller;
use App\Http\Requests\User\Add\AddInterestedCaseRequest;
use App\Http\Requests\User\Add\cancelInterestedCaseRequest;
use App\Services\User\Add\AddInterestedService;
use Exception;
use Illuminate\Http\Request;

class AddController extends Controller
{
    protected AddInterestedService $interestedService ;


    public function __construct(AddInterestedService $interestedService)
    {
        $this->interestedService = $interestedService;
    }

    public function interestedCase(AddInterestedCaseRequest $request)
    {
        try{
            $data = $this->interestedService->AddInterested($request->case_id,$request->party);
            if($data)
                return $this->sendOkResponse('تم ربطك بالقضية');
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // canselInterestedCase
    public function cancelInterestedCase(cancelInterestedCaseRequest $request)
    {
        try{
            $data = $this->interestedService->cancelInterested($request->interest_id);
            if($data)
                return $this->sendOkResponse('تم الغاء الاهتمام بالقضية');
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

}
