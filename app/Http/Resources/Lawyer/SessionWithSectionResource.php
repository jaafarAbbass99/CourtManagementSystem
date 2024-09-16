<?php

namespace App\Http\Resources\Lawyer;

use App\Http\Resources\JudgeResource;
use App\Models\CaseJudge;
use App\Models\Session;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionWithSectionResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'session_number' => $this->session_number,
            'session_date' => $this->session_date,
            'session_time' => $this->session_time,
            'session_type' => __('sessionType.'.$this->session_type->value),
            'session_status' =>__('sessionStatus.'.$this->session_status->value),
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'status' => $this->caseJudge->status ?? 'Unknown',
            'date_close_case' => $this->caseJudge->date_close_case ?? null, 
            "number_case" => $this->caseJudge->full_number,
            "date_open_case" => $this->caseJudge->created_at->format('Y-m-d'),
            'judge' => new JudgeResource($this->caseJudge->judgeSection->judge),  
        ];
    }
}
