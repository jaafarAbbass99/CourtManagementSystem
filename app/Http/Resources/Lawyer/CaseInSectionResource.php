<?php

namespace App\Http\Resources\Lawyer;

use App\Http\Resources\Cases\CaseResources;
use App\Http\Resources\Cases\CasesResources;
use App\Http\Resources\JudgeInSectionResource;
use App\Http\Resources\JudgeResource;
use App\Models\JudgeSection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseInSectionResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            "case" => CaseResources::make($this),
            "with" => $this->pivot->representing,
            "details" => JudgeInSectionResource::collection($this->judgeSections),
            // ShowDetailsCaseResource::collection($this),
        ];
    }
}
