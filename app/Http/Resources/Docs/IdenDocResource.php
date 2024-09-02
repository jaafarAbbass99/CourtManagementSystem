<?php

namespace App\Http\Resources\Docs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class IdenDocResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ,
            "doc_name"=> $this->file->doc_name ,
            "document_path" => $this->file->document_path ,
            'status' => __('Status.'.$this->status->value),
            'type'=> __('reqDocs.'. $this->type->req_doc->value),
            "created_at" => $this->file->created_at->format('Y-m-d') ,
            "updated_at" => $this->file->updated_at->format('Y-m-d') ,
        ];
    }
}
