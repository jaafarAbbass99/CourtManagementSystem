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
            'opening_date' => $this->created_at->format('Y-m-d'),
            'location' => $this->location,
            'court' => new CourtResource($this->whenLoaded('court')),
            'judges' => JudgeSectionResource::collection($this->whenLoaded('judgeSections')),
        ];
    }
}
