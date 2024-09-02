<?php

use App\Http\Controllers\Admin\ProcessingAuth\AdminProcessingAuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\JudgeSectionAdminController;

/**
 * Admin
 * Processing Lawyer's Auth
 * 
 */
Route::prefix('admin')->middleware(['auth:sanctum','check.role.Admin'])->group(function () {

    Route::prefix('/processing-auth')->group(function () {

        Route::get('/get-pending-docs-lawyer/{id}',    
            [AdminProcessingAuthController::class, 'getPendingDocsLawyer'])
            ->name('admin.processingAuth.getPendingDocsLawyer');
            
        Route::get('/get-approved-docs-lawyer/{id}',    
        [AdminProcessingAuthController::class, 'getApprovedDocsLawyer'])
        ->name('admin.processingAuth.getApprovedDocsLawyer');
    
        Route::get('/get-pending-lawyers', 
            [AdminProcessingAuthController::class, 'getPendingLawyers'])
            ->name('admin.processingAuth.getPendingLawyers');
    
        Route::get('/get-rejected-lawyers',
            [AdminProcessingAuthController::class, 'getRejectedLawyers'])
            ->name('admin.processingAuth.getRejectedLawyers');
    
        Route::get('/get-approved-lawyers',
            [AdminProcessingAuthController::class,'getApprovedLawyers'])
            ->name('admin.processingAuth.getApprovedLawyers');
    

        Route::get('/get-rejected-judges',[AdminProcessingAuthController::class, 'getRejectedJudges']);

        Route::get('/get-approved-judges',[AdminProcessingAuthController::class,'getApprovedJudges']);

        Route::get('/get-pinding-judges',[AdminProcessingAuthController::class,'getPendingJudges']);








        Route::get('rejected-docs', [AdminProcessingAuthController::class, 'getRejectedDocs']);
        Route::get('approved-docs', [AdminProcessingAuthController::class, 'getApprovedDocs']);
        Route::get('approved-docs-count', [AdminProcessingAuthController::class, 'getApprovedDocsCount']);
        Route::get('rejected-docs-count', [AdminProcessingAuthController::class, 'getRejectedDocsCount']);
        Route::get('approved-lawyers-count', [AdminProcessingAuthController::class, 'getApprovedLawyersCount']);
        Route::get('rejected-lawyers-count', [AdminProcessingAuthController::class, 'getRejectedLawyersCount']);
        Route::get('pending-lawyers-count', [AdminProcessingAuthController::class, 'getPendingLawyersCount']);
        
    
    
    /**
     * rejectes or approves Lawyeres' docs and account 
     */
    
        Route::post('/rejectes-doc', 
            [AdminProcessingAuthController::class, 'rejectDocument'])
            ->name('admin.reject.document');
    
        Route::post('/approves-doc', 
            [AdminProcessingAuthController::class, 'approveDocument'])
            ->name('admin.approve.document');
    
        Route::post('/approves-account', 
            [AdminProcessingAuthController::class, 'approveAccount'])
            ->name('admin.approve.account');
    
        Route::post('/rejectes-account', 
            [AdminProcessingAuthController::class, 'rejectAccount'])
            ->name('admin.reject.account');
    });

    
    Route::prefix('judge-sections')->group(function () {
        Route::post('add-judge', [JudgeSectionAdminController::class, 'addJudgeToSection']); // إضافة قاضي لقسم محدد
        Route::get('/get-judges/section/{sectionId}', [JudgeSectionAdminController::class, 'getJudgesBySection']); // جلب القضاة في قسم محدد
        Route::delete('delete-judge/{judgeSection}', [JudgeSectionAdminController::class, 'deleteJudgeFromSection']);
    });

});

