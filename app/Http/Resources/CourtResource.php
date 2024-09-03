<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'province' => $this->province,
            "date_open_court" => $this->created_at->format('Y-m-d'),
            "date_update_court" => $this->updated_at->format('Y-m-d'),
            'sections' => SectionResource::collection($this->whenLoaded('sections')), // عرض الأقسام عند تحميلها
        ];
    }
}
