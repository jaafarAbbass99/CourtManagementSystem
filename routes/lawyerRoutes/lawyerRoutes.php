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

    
    // اضافة ملفات الى دعوى موجودة
    Route::post('/add-files/case', [CaseController::class, 'addFilesToCase']);
    // عرض ملفات الدعوى 
    Route::get('/show-my-files/case/{case_id}', [CaseController::class, 'showMyFilesCase']);

    Route::get('/show-files/case/{case_id}/judge/{user_id}', [CaseController::class, 'showFilesCaseByUser']);

    

    Route::middleware('check.joined.Lawyer')->group(function () {
        // 2. فتح دعوى
        Route::post('/open-case', [CaseController::class, 'openCase']);//يجب الانضمام الى المحكمة
        
        // 3. عرض الدعاوى التابعة له في محكمة محددة
        Route::get('/cases-in-court/{court_id}', [CaseController::class, 'showCasesInCourt']);//شرط الانضمام الى تلك المحكمة
    
        // 5. عرض دعوى معينة حسب اسم المدعي والعام والمحكمة
        Route::get('/case', [CaseController::class, 'showCaseByDetails']);//كذلك + شرط الدعوى للمحامي     
        
    });
    
    // عرض الدعاوى حسب الحالة في محكمة (نوع محكمة)
    Route::get('/my_cases_in_court', [CaseController::class, 'showCasesInCourtByStatus']);
    
    //عرض عدد الدعاوى حسب حالتها في محكمة محددة (نوع المحكمة)
    Route::get('/count_my_cases_in_court', [CaseController::class, 'showCountCasesInCourtByStatus']);
    
    // عرض تفاصيل دعوى في قسم محدد
    Route::get('/details-case', [CaseController::class, 'showDetailsCase']);

    // اظهار دعوى من خلال رقمها
    Route::get('/show-case-by-number/{number_case}', [CaseController::class, 'showCaseByNumber']);




    ////////////////////////////////////////////
    //عرض دعاوى حسب حالتها في القسم (مفصولة | غير مفصولة)

    Route::get('/show-cases/cl-op/{status}', [CaseController::class, 'showCasesCloseOrOpenWithDetails']);

    Route::get('/result-count-cases', [CaseController::class, 'getCasesSummary']);

    Route::get('/show-sessions-case/{case_id}/{case_judge_id}' , [CaseController::class ,'showSessionsCase']);

    // /////
    Route::post('/add-order-decision' , [CaseController::class ,'addDecisionOrder']);
    Route::get('/show-order-decision/{id}' , [CaseController::class ,'showDecisionOrder']);
    Route::get('/show-all-order' , [CaseController::class ,'showAllOrder']);

    Route::delete('/delete-order/{order_id}' , [CaseController::class ,'deleteOrder']);

    // 25-9
    //   عرض كل التوكيلات المستلمة
    Route::get('/show-my-received-attorney', [CaseController::class, 'showMyReceivedAttorney']);

    // رفع دعوى لتوكيل
    Route::post('/open-case-for-attorney', [CaseController::class, 'openCaseForAttorney']);

    // معالجة طلبات التوكيل
    Route::post('/accept-attorney-order/{order_id}' , [CaseController::class ,'acceptPowAttOrder']);
    Route::post('/reject-attorney-order/{order_id}' , [CaseController::class ,'rejectPowAttOrder']);
    Route::get('/show-attorney-orders' , [CaseController::class ,'showAttOrders']);
    Route::get('/show-defense-orders' , [CaseController::class ,'showDefOrders']);



});