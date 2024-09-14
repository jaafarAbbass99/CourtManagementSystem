<?php

namespace App\Http\Resources\Judge\Show;

use App\Enums\Representing;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DecisionCaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        
        return [
            'id' => $this->id,
            'session_id' => $this->session_id,
            'case_id' => $this->case_id,
            'summary' => $this->summary,
            'status' => $this->status,
            'favor' => $this->favor != Representing::NOBODY->value ? $this->favor : null,
            'summary' => $this->summary,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'caseDocs' => CaseDocResource::collection($this->whenLoaded('caseDocs')),


        ];
    }
}
