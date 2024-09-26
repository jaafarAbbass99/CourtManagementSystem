<?php

namespace App\Http\Resources\Lawyer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyAttorneyResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id_attorney' => $this->id,
            'case_id' => $this->case_id,
            'representing' => $this->representing,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'order' => new AttorneyOrderResource($this->whenLoaded('order'))
        ];
    }
}
