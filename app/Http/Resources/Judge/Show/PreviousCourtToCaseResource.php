<?php

namespace App\Http\Resources\Judge\Show;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PreviousCourtToCaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status,
            'date_close_case' => $this->date_close_case,
            'judge' => [
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
            ],
            'court' => [
                'rank' => $this->rank,
                'type' => $this->type,
            ],
        ];
    }
}
