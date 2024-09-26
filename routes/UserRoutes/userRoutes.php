<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\User\Add\AddController;
use App\Http\Controllers\User\Show\ShowController;
use Illuminate\Support\Facades\Route;


Route::get('/show-case' , [ShowController::class ,'showCase']);


Route::middleware(['auth:sanctum','check.role.User'])->prefix('/user')->group(function () {
    Route::post('/interested-case' , [AddController::class ,'interestedCase']);

    Route::post('/cancel-interested-case/{interest_id}' , [AddController::class ,'cancelInterestedCase']);
    
    Route::get('/show-interestes' , [CaseController::class ,'showInterestes']);
    
    Route::middleware(['check.interesting'])->group(function () {
        
        Route::get('/show-previous-courts/case/{case_id}' , [ShowController::class ,'showCourtsToCase']);
     
        Route::get('/show-sessions-case/{case_id}/{case_judge_id}' , [CaseController::class ,'showSessionsCase']);
        
        Route::get('/show-attorneys-case/{case_id}' , [CaseController::class ,'showAttorneysCase']);
        
        Route::get('/show-files-judge-case/{case_id}/court_type/{court_type_id}', [CaseController::class, 'showFilesJudgeCaseByCourt']);
        
        Route::post('/add-defense-order', [CaseController::class, 'addDefenseOrder']);
        
        Route::get('/show-defense-case/{case_id}', [CaseController::class, 'showDefenseOrdersInterestCase'])
        ->middleware(['check.InterestAsPartyTwo']);
        
    });

    Route::post('/ok-defense-order', [CaseController::class, 'oKDefenseOrder']);

    Route::post('/cancel-defense-order', [CaseController::class, 'cancelDefenseOrder']);

    // 25-9
    Route::post('/add-attorney-order', [CaseController::class, 'addAttorneyOrder']);
    Route::post('/cancel-attorney-order', [CaseController::class, 'cancelAttorneyOrder']);
    Route::post('/ok-attorney-order', [CaseController::class, 'oKAttorneyOrder']);
    // error
    // تأكد من admin
    Route::get('/show-my-attorney-orders', [CaseController::class, 'showAttorneyOrders']);

    Route::get('/show-attorney-orders/case/{case_id}', [CaseController::class, 'showAttorneyOrdersInterestCase'])
    ->middleware(['check.InterestAsPartyOne']);
    




    
    
    
    
    
});