<?php

namespace App\Http\Controllers\Admin\ProcessingAuth;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProcessingLawyerAuth\AccountIDRequest;
use App\Http\Requests\Admin\ProcessingLawyerAuth\IdenDocumentIDRequest;
use App\Http\Requests\Admin\ProcessingLawyerAuth\LawyerIDRequest;
use App\Http\Resources\AccountUserResource;
use App\Http\Resources\ProfileUserResource;
use App\Services\Admin\ProcessingAuthServices\IdenDocumentService;
use App\Services\Admin\ProcessingAuthServices\LawyerGetService;
use GuzzleHttp\Psr7\Request;
use Illuminate\Http\JsonResponse;
use Psy\CodeCleaner\ReturnTypePass;

class AdminProcessingAuthController extends Controller
{
    protected $lawyerService;
    protected $documentService;

    public function __construct(LawyerGetService $lawyerService,IdenDocumentService $documentService)
    {
        $this->lawyerService = $lawyerService;
        $this->documentService = $documentService;

    }

    // 1. إظهار كافة الوثائق المعلقة لمحامي ما
    public function getPendingDocsLawyer($id): JsonResponse
    {
        try {
            $pendingDocs = $this->lawyerService->getDocsForLawyer($id,Status::PENDING);
            return $this->sendResponse($pendingDocs);
        }
        catch(\Exception $e){
            return $this->sendError('An error occurred while fetching pending documents.',500);
        }
    }
// 2. إظهار كافة الوثائق المقبولة لمحامي ما
    public function getApprovedDocsLawyer($id): JsonResponse
    {
        try {
            $pendingDocs = $this->lawyerService->getDocsForLawyer($id,Status::APPROVED);
            return $this->sendResponse($pendingDocs);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching approved documents.',500);
        }
    }
// 3. إظهار كافة الوثائق المرفوضة لمحامي ما
    public function getRejectedDocsLawyer($id): JsonResponse
    {
        try {
            $pendingDocs = $this->lawyerService->getDocsForLawyer($id,Status::REJECTED);
            return $this->sendResponse($pendingDocs);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching rejected documents.',500);
        }
    }



    // 2. إظهار المحامين المعلقين
    public function getPendingLawyers()
    {
        try{
            $pendingLawyers = $this->lawyerService->getLawyers(Status::PENDING);

            return $this->sendResponse($pendingLawyers);
        }
        catch(\Exception $e) {
            return $this->sendError($e.'An error occurred while fetching pending documents.',500);
        }

    }

    // 3. إظهار المحامين المرفوضين
    public function getRejectedLawyers(): JsonResponse
    {
        try{
            $rejectedLawyers = $this->lawyerService->getLawyers(Status::REJECTED);
            return $this->sendResponse($rejectedLawyers);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching pending documents.',500);
        }
    }

    // 4. إظهار القضاة المقبولين
    public function getApprovedLawyers(): JsonResponse
    {
        try{
            $approvedLawyers = $this->lawyerService->getLawyers(Status::APPROVED);
            return $this->sendResponse($approvedLawyers);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching approved documents.',500);
        }

    }

    /**
     * القضاة 
     */
    public function getRejectedJudges(): JsonResponse
    {
        try{
            $rejectedLawyers = $this->lawyerService->getJudges(Status::REJECTED);
            return $this->sendResponse($rejectedLawyers);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching pending documents.',500);
        }
    }

    // 4. إظهار القضاة المقبولين
    public function getApprovedJudges(): JsonResponse
    {
        try{
            $approvedLawyers = $this->lawyerService->getJudges(Status::APPROVED);
            return $this->sendResponse($approvedLawyers);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching approved documents.',500);
        }
        
    }

    
    public function getPendingJudges(): JsonResponse
    {
        try{
            $approvedLawyers = $this->lawyerService->getJudges(Status::PENDING);
            return $this->sendResponse($approvedLawyers);
        }
        catch(\Exception $e) {
            return $this->sendError('An error occurred while fetching approved documents.',500);
        }
        
    }


/**
 * rejects,approves idenDocs' and Accounts' lawyer
 */

    // 1. رفض الوثيقة
    public function rejectDocument(IdenDocumentIDRequest $request): JsonResponse
    {
        try {
            $data=$this->documentService->changeStatusDocument($request->document_id , Status::REJECTED );

            return $this->sendResponse($data,'Document rejected successfully.');

        } catch (\Exception $e) {
            return $this->sendError('An error occurred while rejecting the document.');
        }
    }

    // 2. قبول الوثيقة
    public function approveDocument(IdenDocumentIDRequest $request): JsonResponse
    {
        try {
            $this->documentService->changeStatusDocument($request->document_id , Status::APPROVED);
            return $this->sendResponse('','Document approved successfully.');
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while rejecting the document.');
        }
    }

    // 3. قبول حساب
    public function approveAccount(AccountIDRequest $request): JsonResponse
    {
        try {
            $data = $this->lawyerService->approveAccount($request->account_id);
            if($data == false)
                return $this->sendError('Account rejected');
            return $this->sendResponse($data,'Account approved successfully.');
            
        } catch (\Exception $e) {
            return $this->sendError('An error occurred while approving the Account.');
        }
    }

    // 4. رفض حساب
    public function rejectAccount(AccountIDRequest $request): JsonResponse
    {
        try {
            $data = $this->lawyerService->rejectAccount($request->account_id);
            
            return $this->sendResponse($data,'Account rejected successfully.');

        } catch (\Exception $e) {
            return $this->sendError('An error occurred while rejecting the Account.');
        }
    }


/**
 * 
 * 
 *  */        
    public function getRejectedDocs(): JsonResponse
    {
        try {
            $rejectedDocs = $this->documentService->getDocs(Status::REJECTED);
            return $this->sendResponse($rejectedDocs);
        } catch (\Exception $e) {
            // return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }

    // 2. إظهار الوثائق المقبولة
    public function getApprovedDocs(): JsonResponse
    {
        try {
            $approvedDocs = $this->documentService->getDocs(Status::APPROVED);
            return $this->sendResponse($approvedDocs);
        } catch (\Exception $e) {
            return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }

    // 3. عدد الوثائق المقبولة
    public function getApprovedDocsCount(): JsonResponse
    {
        try {
            $count = $this->documentService->getDocsCount(Status::APPROVED);
            return $this->sendResponse(['count' => $count]);
        } catch (\Exception $e) {
            return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }

    // 4. عدد الوثائق المرفوضة
    public function getRejectedDocsCount(): JsonResponse
    {
        try {
            $count = $this->documentService->getDocsCount(Status::REJECTED);
            return $this->sendResponse(['count' => $count]);
        } catch (\Exception $e) {
            return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }

    // 5. عدد المحامين المقبولين
    public function getApprovedLawyersCount(): JsonResponse
    {
        try {
            $count = $this->lawyerService->getLawyersCount(Status::APPROVED);
            return $this->sendResponse(['count' => $count]);
        } catch (\Exception $e) {
            return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }

    // 6. عدد المحامين المرفوضين
    public function getRejectedLawyersCount(): JsonResponse
    {
        try {
            $count = $this->lawyerService->getLawyersCount(Status::REJECTED);
            return $this->sendResponse(['count' => $count]);
        } catch (\Exception $e) {
            //return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }

    // 7. عدد المحامين المعلقين
    public function getPendingLawyersCount(): JsonResponse
    {
        try {
            $count = $this->lawyerService->getLawyersCount(Status::PENDING);
            return $this->sendResponse(['count' => $count]);
        } catch (\Exception $e) {
            return $this->sendError(__('messages.error_fetching_data'), 500);
        }
    }


}
