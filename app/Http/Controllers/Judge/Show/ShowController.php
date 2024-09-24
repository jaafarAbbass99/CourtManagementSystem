<?php

namespace App\Http\Controllers\Judge\Show;

use App\Enums\TypeCourt;
use App\Http\Controllers\Controller;
use App\Http\Resources\Cases\CaseResources;
use App\Http\Resources\CourtTypeResource;
use App\Http\Resources\Judge\Show\NewCaseResource;
use App\Http\Resources\Judge\Show\PreviousCourtToCaseResource;
use App\Http\Resources\Judge\Show\SessionsByDateResource;
use App\Http\Resources\Judge\Show\SessionsCaseResource;
use App\Models\CourtType;
use App\Services\Judge\Show\ShowCasesService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShowController extends Controller
{
    protected ShowCasesService $showService ;
    
    public function __construct(ShowCasesService $showService)
    {

        $this->showService = $showService;
    }
    
    public function showNewCases()
    {
        try{
            $data = $this->showService->getNewCases(Auth::user()->user->id);
            $result = NewCaseResource::collection($data);
            
            return $this->sendResponse($result);
                
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }
 
    public function showCase($case_id)
    {
        try{
            $data = $this->showService->getCase(Auth::user()->user->id,$case_id);
           
            $result = NewCaseResource::collection($data);
            
            return $this->sendResponse($result);
                
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showTypeCourt($court_id)
    {
        try{
            $data = CourtType::where('court_id',$court_id)
                    ->get();
           
            $result = CourtTypeResource::collection($data);
            
            return $this->sendResponse($result);
                
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    public function showSessionsCase($case_id)
    {
        try{
            $data = $this->showService->getSessionsCase(Auth::user()->user->id , $case_id);

            $result = SessionsCaseResource::collection($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // showSessionsCaseForPreviousCourt
    public function showSessionsCaseForPreviousCourt($case_judge_id)
    {
        try{
            $data = $this->showService->getSessionsCaseInPreviousCase($case_judge_id);

            $result = SessionsCaseResource::collection($data);
            
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }


    // showUnAvilableDateTime

    public function showUnAvailableDateTime()
    {
        try{
            $data = $this->showService->getUnAvailableDateTime(Auth::user()->user->id);

            return $this->sendResponse($data);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // showTodaySession

    public function showTodaySessions()
    {
        try{
            $data = $this->showService->getTodaySessions();
            
            $result = SessionsByDateResource::collection($data);
            return $this->sendResponse($result);
            
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    
    
    // showMonthlySessions
    public function showMonthlySessions()
    {
        try{
            $data = $this->showService->getMonthlySessions();
            
            $result = SessionsByDateResource::collection($data);
            return $this->sendResponse($result);
   
        }catch(Exception $e){
            return $this->sendError(
                $e->getMessage()
            );
        }
    }

    // showPreviousCourtsToCase
    public function showPreviousCourtsToCase($case_id)
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
