<?php

namespace App\Http\Resources\Lawyer;

use App\Http\Resources\CourtResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MyCourtsResources extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'my_court' => CourtResource::make($this->court),
            'details' => [
                'date_joined'=>$this->created_at->format('Y-m-h'),
                'date_update_joined'=>$this->updated_at->format('Y-m-h'),
            ]
        ];
    }
}
