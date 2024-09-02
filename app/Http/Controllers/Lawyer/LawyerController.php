<?php

namespace App\Http\Controllers\Lawyer;

use App\Http\Controllers\Controller;
use App\Http\Requests\JoinCourtRequest;
use App\Models\LawyerCourt;
use App\Services\Lawyer\LawyerService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LawyerController extends Controller
{

    protected $lawyerService;

    public function __construct(LawyerService $lawyerService)
    {
        $this->lawyerService = $lawyerService;
    }

    public function joinCourt(JoinCourtRequest $request)
    {

        $id_user = Auth::user()->user->id;
        $existing = LawyerCourt::where('user_id', $id_user)
                                ->where('court_id', $request->court_id)
                                ->first();

        if ($existing) {
            return $this->sendError('المحامي منضم بالفعل إلى هذه المحكمة',409);
        }

        $response = $this->lawyerService->joinCourt($id_user, $request->court_id, $request->assigned_date);
        
        return $this->sendOkResponse('تم الانضمام إلى المحكمة بنجاح.');

    }
}
