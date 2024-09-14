<?php

namespace App\Http\Resources\Judge\Show;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SessionsByDateResource extends JsonResource
{
   
    public function toArray(Request $request): array
    {
        return [
            'session'=>SessionsCaseResource::make($this),
            'case_id'=> $this->case_id,
            'full_number' => $this->full_number,
            'status' => $this->status,
        ];
    }
}
