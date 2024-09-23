<?php

namespace App\Http\Resources;

use App\Http\Resources\Cases\CaseResources;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterestResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'party' => $this->party,
            'is_admin' => $this->is_admin,
            'date_interesting' => $this->created_at,
            'case' => new CaseResources($this->whenLoaded('case')), // ربط البيانات الخاصة بالقضية
        ];

    }
}
