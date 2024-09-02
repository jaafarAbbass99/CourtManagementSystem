<?php

namespace App\Http\Resources\Cases;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseTypeResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' =>$this->name, 
            'short_form' => $this->short_form ,
            'dispute_type_name' => $this->disputeType->name,
        ];
    }
}
