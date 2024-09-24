<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CourtTypeResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id ,
            'type' => $this->type,
            'type_form'=> $this->type_form ,
            'city'=> $this->city ,
        ];
    }
}
