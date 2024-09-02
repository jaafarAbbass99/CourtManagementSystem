<?php

namespace App\Http\Resources\Cases;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DesputeTypeResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'name' =>$this->name,
        ];
    }
}
