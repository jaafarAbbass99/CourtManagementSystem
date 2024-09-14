<?php

namespace App\Http\Resources\Judge\Show;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseDocResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'summary' => $this->summary,
            'type' => $this->type,
            'doc_name' => $this->file->doc_name,
            'document_path' => $this->file->document_path,
            // 'file' => new DocumentResource($this->whenLoaded('file')),
        ];
    }
}
