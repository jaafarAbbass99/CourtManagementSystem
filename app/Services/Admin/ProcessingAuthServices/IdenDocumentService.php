<?php 

namespace App\Services\Admin\ProcessingAuthServices;

use App\Enums\Status;
use App\Http\Resources\Docs\IdenDocResource;
use App\Models\Document;
use App\Models\IdenDoc;

class IdenDocumentService
{

    /**
     * اظهار الوثائق حسب الحالة 
     */
    public function getDocs(Status $status)
    {
        $result = [];
        $data = IdenDoc::where('status',$status->value)
                    ->with(['file','type'])
                    ->paginate(10);
        if($data)    
            $restult = IdenDocResource::collection($data);
        return $restult ;
    }

    /**
     * اظهار عدد الوثائق حسب الحالة
     */
    public function getDocsCount(Status $status)
    {
        return IdenDoc::where('status',$status->value)->count();
    }


    // 5. رفض وثيقة
    public function changeStatusDocument($documentId,$status)
    {
        $document = IdenDoc::findOrFail($documentId);
        if($document->action != $status )
            $document->update(['status' => $status->value ]);
        return $document;
    }

    public function UniqueReqDocForUser($user_id , $req_doc_id){
        return IdenDoc::where('user_id', $user_id)
                ->where('req_doc_id', $req_doc_id)
                ->exists();
    }

    public function addIdenDoc(array $data){
        $doc = Document::create([
            'doc_name' => $data['file_name'],
            'document_path' => $data['file_path'] . "/" ,
        ]);

        return  IdenDoc::create([
            'doc_id' => $doc->id,
            'req_doc_id' => $data['req_doc_id']  ,
            'user_id' => $data['user_id'],
            'status' => Status::PENDING->value ,
        ]);
        
    }



 
}
