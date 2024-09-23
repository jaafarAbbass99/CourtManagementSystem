<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttorneysCaseResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'representing' => $this->representing,
            'date_attorney' => $this->created_at,
            'lawyer_in_court' => new LawyerInCourtResource($this->lawyerInCourt),
        ];
    }
}
