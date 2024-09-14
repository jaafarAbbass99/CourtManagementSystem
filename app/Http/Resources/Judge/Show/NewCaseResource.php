<?php

namespace App\Http\Resources\Judge\Show;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewCaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'case_number' => $this->pivot->full_number,
            'number' => $this->number,
            'full_number' => $this->full_number,
            'party_one' => $this->party_one,
            'party_two' => $this->party_two,
            'subject' => $this->subject,
            'court_type' => $this->courtType->type,
            'lawyers' => LawyerResource::collection($this->lawyers),
            'status' => $this->pivot->status,
            'is_seen' => $this->pivot->is_seen ,
            'date_close_case' => $this->pivot->date_close_case,
        ];
    }
}
