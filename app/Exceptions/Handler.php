<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;

class Handler extends ExceptionHandler
{
    
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    protected $dontReport = [
        AuthorizationException::class,
    ];


    public function render($request, Throwable $exception)
    {
       
        if ($exception instanceof AuthorizationException) {
            return response()->json([
                'success' => false,
                'message' => 'لا يوجد صلاحيات'
            ], 403);
        }

        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'success' => false,
                'message' => 'ما طلبته غير موجود'//' القاضي غير موجود في القسم'
            ], 404);
        }

        // معالجة الاستثناءات الأخرى بشكل طبيعي
        return parent::render($request, $exception);
    }
        

}
