<?php

use App\Http\Controllers\Auth\Lawyer\JoinRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

require base_path('routes/auth.php');
require base_path('routes/adminRoutes.php');
require base_path('routes/lawyerRoutes/lawyerRoutes.php');
require base_path('routes/JudgeRoutes/judgeRoutes.php');
require base_path('routes/UserRoutes/userRoutes.php');



Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});



