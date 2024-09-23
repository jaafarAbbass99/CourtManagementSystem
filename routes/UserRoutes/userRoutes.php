<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\User\Add\AddController;
use App\Http\Controllers\User\Show\ShowController;
use Illuminate\Support\Facades\Route;


Route::get('/show-case' , [ShowController::class ,'showCase']);


Route::middleware(['auth:sanctum','check.role.User'])->prefix('/user')->group(function () {
    Route::post('/interested-case' , [AddController::class ,'interestedCase']);

    Route::post('/cansel-interested-case/{interest_id}' , [AddController::class ,'canselInterestedCase']);
    
    Route::get('/show-interestes' , [CaseController::class ,'showInterestes']);

    Route::middleware(['check.interesting'])->group(function () {
        
        Route::get('/show-previous-courts/case/{case_id}' , [ShowController::class ,'showCourtsToCase']);
    
        Route::get('/show-sessions-case/{case_id}/{case_judge_id}' , [CaseController::class ,'showSessionsCase']);
    
        Route::get('/show-attorneys-case/{case_id}' , [CaseController::class ,'showAttorneysCase']);
        

    });



    

});