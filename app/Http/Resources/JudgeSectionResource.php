<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Symfony\Component\HttpKernel\Profiler\Profile;

class JudgeSectionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'role_in_section' => __('depsUserRole.'.$this->role),
            'judge' => new ProfileUserResource($this->whenLoaded('judge')), // عرض معلومات القاضي عند تحميلها (افترض وجود UserResource)
            'section' => new SectionResource($this->whenLoaded('section')), // عرض معلومات القسم عند تحميلها
            'court' => new CourtResource($this->whenLoaded('court')), // عرض معلومات المحكمة عند تحميلها
        ];
    }
}
