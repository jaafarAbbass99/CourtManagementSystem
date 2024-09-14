<?php

namespace App\Http\Resources\Lawyer;

use App\Http\Resources\JudgeResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ShowDetailsCaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        $section = $this->judgeSection->section; 
        $judge = $this->judgeSection->judge;

        return [
            'in_section' => [
                "id" => $this->id ,
                "section_number" => $section->section_number ,
                "section_location" => $section->location ,
                "status_case" => $this->status ,
                "base_number" => $this->base_number,
                "number_case" => $this->full_number,
                "date_open_case" => $this->created_at->format('Y-m-d'),
            ],
            "judge" => JudgeResource::make($judge),
        ];
                
    }
}
