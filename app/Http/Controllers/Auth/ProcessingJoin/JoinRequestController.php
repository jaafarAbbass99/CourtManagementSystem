<?php

namespace App\Http\Controllers\Auth\ProcessingJoin;

use App\Enums\Status;
use App\Exceptions\DataAlreadyExistsException;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileRequest;
use App\Models\Document;
use App\Models\IdenDoc;
use App\Models\RequiredIdeDoc;
use App\Services\Admin\ProcessingAuthServices\IdenDocumentService;
use Illuminate\Http\JsonResponse;


class JoinRequestController extends Controller
{
    
    public function addDocsForJoin(FileRequest $request , IdenDocumentService $service): JsonResponse
    {
        $file = $request->file('file');
        $fileName = $this->getFileName($file);

        $filePath = "docs_join/{$request->user()->id}"."_".$request->user()->user_name;


        $profile = $request->user()->user;

        $id_req_doc = RequiredIdeDoc::where('req_doc',$request->req_doc)
        ->where('for',$profile->role)->value('id');
        
        if($service->UniqueReqDocForUser($profile->id , $id_req_doc )){
            throw new DataAlreadyExistsException();
        }

        $iden_doc = $service->addIdenDoc([
            'file_name' => $fileName ,
            'file_path' => $filePath . "/",
            'req_doc_id' => $id_req_doc ,
            'user_id' => $request->user()->id
        ]);

        if($iden_doc){
            $file->storeAs($filePath, $fileName,'public');

            return $this->sendResponse($iden_doc,
                'Documents submitted successfully for joining the system.');
        }

           
    }

    private function getFileName($file)
    {
        return  now()->format('Ymd_His').'_' .$file->getClientOriginalName();
    }

}
