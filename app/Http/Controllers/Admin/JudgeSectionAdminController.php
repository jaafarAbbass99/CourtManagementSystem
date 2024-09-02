<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\AddJudgeSectionRequest;
use App\Http\Resources\JudgeSectionResource;
use App\Models\JudgeSection;
use App\Services\Admin\ProcessingAuthServices\JudgeSectionService;
use Exception;
use GuzzleHttp\Psr7\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class JudgeSectionAdminController extends Controller
{
    protected $judgeSectionService;

    public function __construct(JudgeSectionService $judgeSectionService)
    {
        $this->judgeSectionService = $judgeSectionService;
    }

    // إضافة قاضي إلى قسم محدد
    public function addJudgeToSection(AddJudgeSectionRequest $request)
    {
        try {
            $data = $this->judgeSectionService->addJudgeToSection($request->all());
            return $this->sendResponse($data,'تم إضافة القاضي بنجاح');
        } catch (\Exception $e) {
            return $this->sendError('لم يتم اضافة القاضي');
        }
    }

    // جلب جميع القضاة في قسم محدد
    public function getJudgesBySection($sectionId)
    {
        $judges = $this->judgeSectionService->getJudgesBySection($sectionId);
        if(!$judges->isEmpty())
            return JudgeSectionResource::collection($judges);
        return $this->sendResponse('','لا يوجد النتائج لعرضها');
    }

    public function deleteJudgeFromSection(JudgeSection $judgeSection)
    {   
        try {
            $this->judgeSectionService->deleteJudgeFromSection($judgeSection);
            return $this->sendOkResponse('Judge removed from the section successfully.');
        } catch (Exception $e) {
            return $this->sendError('لم يتم حذف القاضي');
        }
    }

}
