<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderAttorneyResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status_order' => __('status.'.$this->status_order->value),
            'date_order' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'lawyer' => new ProfileUserResource($this->whenLoaded('lawyerUser'))
        ];
    }
}
