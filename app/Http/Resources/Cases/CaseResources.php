<?php

namespace App\Http\Resources\Cases;

use App\Http\Resources\CourtResource;
use App\Http\Resources\CourtTypeResource;
use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CaseResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ,
            "full_number"=> $this->full_number ,
            "party_one" => $this->party_one ,
            "party_two" => $this->party_two ,
            "subject"=> $this->subject ,
            "case_type" => CaseTypeResources::make($this->caseType),
            "date_open_case" => $this->created_at->format('Y-m-d') ,
            "date_updated_case" => $this->updated_at->format('Y-m-d') ,
            'in' => CourtResource::make($this->courtType->court),
        ];
    }
}
