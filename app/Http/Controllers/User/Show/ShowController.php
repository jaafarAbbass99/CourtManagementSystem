<?php

namespace App\Http\Controllers\User\Show;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\Show\ShowCaseUserRequest;
use App\Http\Resources\Judge\Show\PreviousCourtToCaseResource;
use App\Services\User\Show\ShowCasesService;
use Exception;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    protected ShowCasesService $showService ;
    
    public function __construct(ShowCasesService $showService)
    {

        $this->showService = $showService;
    }
    
    public function showCase(ShowCaseUserRequest $request)
    {
        try{
            $data = $this->showService->getCases($request->all());
            return $data ;
            // $result = NewCaseResource::collection($data);
            
            // return $this->sendResponse($result);
                
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    public function showCourtsToCase($case_id)
    {
        try{
            $data = $this->showService->getPreviousCourtToCase($case_id);
            $result = PreviousCourtToCaseResource::collection($data);
            return $this->sendResponse($result);
   
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }
 
}
