<?php

namespace App\Http\Resources\Judge\Show;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionsCaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'session_number' => $this->session_number,
            'session_date' => $this->session_date,
            'session_time' => $this->session_time,
            'session_type' => __('sessionType.'.$this->session_type->value),
            'session_status' =>__('sessionStatus.'.$this->session_status->value),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'decision' => $this->decision ? DecisionCaseResource::collection($this->decision) : [],
        ];
    }
}
