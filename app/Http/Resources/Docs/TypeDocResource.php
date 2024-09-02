<?php

namespace App\Http\Resources\Docs;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TypeDocResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "name_doc" =>__('reqDocs.'. $this->req_doc->value),
            "for" => __('role.'. $this->for->value),
        ];
    }
}
