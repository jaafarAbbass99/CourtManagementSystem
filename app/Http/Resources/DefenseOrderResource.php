<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DefenseOrderResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order' => new OrderAttorneyResource($this->whenLoaded('order')),
        ];
    }
}
