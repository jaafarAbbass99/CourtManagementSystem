<?php

use App\Http\Controllers\Employee\EmployeeController;
use Illuminate\Support\Facades\Route;


Route::middleware(['auth:sanctum','check.role.Employee'])->prefix('/employee')->group(function () {
    
    Route::get('show-interest-in-my-court',[EmployeeController::class, 'showInterestInMyCourt']);
    Route::get('show-interest-in-my-court/name',[EmployeeController::class, 'showInterestInMyCourtByName']);


    Route::post('/make-interester-admin/{interest_id}', [EmployeeController::class, 'makeInteresterAdmin']);

    Route::post('/cancel-interester-admin/{interest_id}', [EmployeeController::class, 'cancelInteresterAdmin']);

    // //////////////////////////////////////////////////////////////////////////////
    Route::get('show-un-processed-orders',[EmployeeController::class, 'showAllOrder']);

    Route::get('show-processed-orders',[EmployeeController::class, 'showProcessedOrder']);


    Route::post('/ok-order-decision/{order_id}' , [EmployeeController::class ,'MakeOkDecisionOrder']);

    Route::post('/cancel-order-decision/{order_id}' , [EmployeeController::class ,'cancelOkDecisionOrder']);





    
});