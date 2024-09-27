<?php

namespace App\Http\Resources\Employee;

use App\Http\Resources\Cases\CaseResources;
use App\Http\Resources\ProfileUserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InterestEmployeeResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'party' => __('party.'.$this->party->value),
            'is_admin' => $this->is_admin,
            'date_interesting' => $this->created_at,
            'interester' => new ProfileUserResource($this->whenLoaded('user')),
            'case' => new CaseResources($this->whenLoaded('case')), 
        ];
    }
}
