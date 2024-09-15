<?php

namespace App\Http\Controllers\Auth\ProcessingJoin;

use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\FileUpdateRequest;
use App\Models\IdenDoc;
use Illuminate\Support\Facades\Storage;



class updateRejectedIdDocsController extends Controller
{
    public function __invoke(FileUpdateRequest $request)//: JsonResponse
    {
        $document = IdenDoc::where('id', $request->doc_id)
                            ->where('user_id', $request->user()->user->id)
                            ->where('status', Status::REJECTED->value)
                            ->first();

        // إذا لم يتم العثور على الوثيقة
        if (!$document) {
            return $this->sendError('Document not found or not eligible for update.',404) ;
        }
        
        // حذف الوثيقة القديمة إذا كانت موجودة
        $pathName_doc = $document->file->document_path.$document->file->doc_name;

        if (Storage::exists($pathName_doc)) {
            Storage::delete($pathName_doc);
        }

        $nameDoc = now()->format('Ymd_His'). '_' .$request->file('file')->getClientOriginalName();

        // رفع الوثيقة الجديدة
         $filePath = $request->file('file')->storeAs($document->file->document_path,$nameDoc,'public');

        // تحديث معلومات الوثيقة
        $document->update([
            'updated_at' => time(), 
            'status' => Status::PENDING->value , // إعادة الوثيقة إلى الحالة المعلقة للمراجعة
        ]);
        $document->file->update([
            'doc_name' => $nameDoc,
        ]);

        return $this->sendResponse($document , 'Document updated and resubmitted for review.');
        
    }
}
