<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SectionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'section_number' => $this->section_number,
            'opening_date' => $this->opening_date->format('Y-m-d'),
            'location' => $this->location,
            'court_id' => $this->court_id,
            'court' => new CourtResource($this->whenLoaded('court')),
            'judges' => JudgeSectionResource::collection($this->whenLoaded('judgeSections')),
        ];
    }
}
