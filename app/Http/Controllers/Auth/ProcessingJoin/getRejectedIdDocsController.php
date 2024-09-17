<?php

namespace App\Http\Controllers\Auth\ProcessingJoin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Resources\Docs\IdenDocResource;
use App\Http\Resources\Docs\TypeDocResource;
use App\Models\Document;
use App\Models\IdenDoc;
use App\Services\Admin\ProcessingAuthServices\LawyerGetService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function PHPUnit\Framework\isNull;

class getRejectedIdDocsController extends Controller
{
    public function __invoke(LawyerGetService $lawyerService , Request $request)//: JsonResponse
    {
        try {
            $user = $request->user();
            
            $result = $lawyerService->getDocsForLawyer($user->user_id,Status::REJECTED);
            
            return $this->sendResponse($result,'Rejected documents retrieved successfully.');
            
        } catch(Exception $e){
            return $this->sendError('An error occurred while retrieving rejected documents.');
        }
    }
}
