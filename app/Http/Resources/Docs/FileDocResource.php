<?php

namespace App\Http\Resources\Docs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FileDocResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            "doc_name" => $this->doc_name,
            "document_path" => $this->document_path,
            "date_of_uplode" => $this->created_at,
            "date_of_update" => $this->updated_at,
        ];
    }
}
