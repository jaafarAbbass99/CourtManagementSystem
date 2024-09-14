<?php

namespace App\Http\Resources\Cases;

use App\Http\Resources\CourtResource;
use App\Http\Resources\CourtTypeResource;
use App\Http\Resources\SectionResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CasesResources extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->case->id ,
            "full_number"=> $this->case->full_number ,
            "party_one" => $this->case->party_one ,
            "party_two" => $this->case->party_two ,
            "subject"=> $this->case->subject ,
            'exist_id' => $this->case->exist_now,
            "case_type" => CaseTypeResources::make($this->case->caseType),
            'court' => CourtTypeResource::make($this->case->courtType),
            "representing" => $this->representing , 
            "result" => $this->win->get ,
            "date_Attorney" => $this->created_at->format('Y-m-d') ,
            "date_open_case" => $this->case->created_at->format('Y-m-d') ,
            "date_updated_case" => $this->case->updated_at->format('Y-m-d') ,
        ];
    }
}
