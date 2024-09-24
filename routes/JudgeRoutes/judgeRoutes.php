<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\Judge\Add\AddController;
use App\Http\Controllers\Judge\Show\ShowController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum','check.role.Judge'])->prefix('/judge')->group(function () {

    Route::get('/show-new-cases' , [ShowController::class ,'showNewCases']);

    Route::get('/show-case/{case_id}', [ShowController::class ,'showCase']);

    Route::get('/show-sessions-case/{case_id}' , [ShowController::class ,'showSessionsCase']);
    
    Route::get('/previous-court/show-sessions-case/{case_judge_id}' , [ShowController::class ,'showSessionsCaseForPreviousCourt']);
    
    Route::get('/show-today-sessions' , [ShowController::class ,'showTodaySessions']);

    Route::get('/show-monthly-sessions' , [ShowController::class ,'showMonthlySessions']);

    Route::get('/show-un-available-date' , [ShowController::class ,'showUnAvailableDateTime']);
    
    Route::get('/show-previous-courts/case/{case_id}' , [ShowController::class ,'showPreviousCourtsToCase']);

    Route::get('/show-my-files/case/{case_id}', [CaseController::class, 'showMyFilesCase']);

    Route::get('/show-files/case/{case_id}/lawyer/{user_id}', [CaseController::class, 'showFilesCaseByUser']);


    // Add-Decision-case
    Route::post('/add-decision-case' , [AddController::class ,'AddDecisionToCase']);
    
    Route::post('/add-next-session-case' , [AddController::class ,'AddSessionToCase']);
    
    Route::post('/make-case-seen/{case_id}' , [AddController::class ,'makeCaseSeen']);
    Route::post('/make-case-close/{case_id}' , [AddController::class ,'makeCaseClose']);
    
    Route::post('/make-session-completed/{session_id}' , [AddController::class ,'makeSessionCompleted']); 
    Route::post('/make-session-cancelled/{session_id}' , [AddController::class ,'makeSessionCancelled']);



  
});


