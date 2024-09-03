<?php

use App\Http\Controllers\CaseController;
use App\Http\Controllers\Lawyer\LawyerController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum','check.role.Lawyer'])->prefix('/lawyer')->group(function () {
    // 1. الانضمام إلى محكمة
    Route::post('/join-court', [LawyerController::class, 'joinCourt']);
    
    Route::get('/my_courts', [LawyerController::class, 'showMyCourts']);
    
    // 4. عرض الدعاوى التابعة له في كل المحاكم المنضم لها
    Route::get('/cases', [CaseController::class, 'showAllCases']);//شرط هل الدعاوى العائدة للمحامي

    Route::middleware('check.joined.Lawyer')->group(function () {
        // 2. فتح دعوى
        Route::post('/open-case', [CaseController::class, 'openCase']);//يجب الانضمام الى المحكمة
    
        // 3. عرض الدعاوى التابعة له في محكمة محددة
        Route::get('/cases-in-court/{court_id}', [CaseController::class, 'showCasesInCourt']);//شرط الانضمام الى تلك المحكمة
    
        // 5. عرض دعوى معينة حسب اسم المدعي والعام والمحكمة
        Route::get('/case', [CaseController::class, 'showCaseByDetails']);//كذلك + شرط الدعوى للمحامي 
        
        

    });

    Route::get('/show-case-by-number/{number_case}', [CaseController::class, 'showCaseByNumber']);
});