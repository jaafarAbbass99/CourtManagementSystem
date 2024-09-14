<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class JudgeInSectionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'section_id' => $this->pivot->id,
            'status' => $this->pivot->status ?? 'Unknown',
            'date_close_case' => $this->pivot->date_close_case ?? null, 
            "number_case" => $this->pivot->full_number,
            "date_open_case" => $this->pivot->created_at->format('Y-m-d'),
            'judge' => new JudgeResource($this->judge),
            'court' => CourtTypeResource::make($this->courtType),
        ];
    }
}
