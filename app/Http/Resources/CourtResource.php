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
            'sections' => SectionResource::collection($this->whenLoaded('sections')), // عرض الأقسام عند تحميلها
        ];
    }
}
