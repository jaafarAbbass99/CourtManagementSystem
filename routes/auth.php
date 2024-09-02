<?php

use App\Http\Controllers\Auth\ProcessingJoin\getRejectedIdDocsController;
use App\Http\Controllers\Auth\ProcessingJoin\JoinRequestController;
use App\Http\Controllers\Auth\ProcessingJoin\updateRejectedIdDocsController;
use App\Http\Controllers\Auth\LoginUserController;
use App\Http\Controllers\Auth\LogoutUserController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\VerifyEmailController;

use Illuminate\Support\Facades\Route;



Route::prefix('auth')->group(function () {
    // مسار التسجيل
    Route::post('/register', [RegisteredUserController::class, 'store'])
        ->name('register');

    //مسار تسجيل الدخول
    Route::post('/login', [LoginUserController::class, 'store'])
        ->name('login');

    // مسار تسجيل الخروج
    Route::post('/logout', [LogoutUserController::class, 'destroy'])
        ->middleware('auth:sanctum') // تأكد من استخدام Sanctum إذا كنت تستخدمه
        ->name('logout');


    Route::middleware(['auth:sanctum', 'check.Verified.LawyerORJudg'])->prefix('processing_join')->group(function () {

        // إضافة مستندات للانضمام
        Route::post('/add-docs-for-join', [JoinRequestController::class, 'addDocsForJoin'])
            ->name('lawyer.addDocsForJoin');
           

        // جلب الوثائق المرفوضة
        Route::get('/get-rejected-id-docs', getRejectedIdDocsController::class)
            ->name('lawyer.getRejectedIdDocs');

        // تحديث الوثائق المرفوضة
        Route::post('/update-rejected-id-doc', updateRejectedIdDocsController::class)
            ->name('lawyer.updateRejectedIdDocs');

    });
    
});

Route::prefix('email')->group(function () {
    // مسار التحقق من البريد الإلكتروني
    Route::get('/verify/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');
});


























// Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('password.email');

// Route::post('/reset-password', [NewPasswordController::class, 'store'])
//                 ->middleware('guest')
//                 ->name('password.store');

// Route::post('/email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
//                 ->middleware(['auth', 'throttle:6,1'])
//                 ->name('verification.send');
