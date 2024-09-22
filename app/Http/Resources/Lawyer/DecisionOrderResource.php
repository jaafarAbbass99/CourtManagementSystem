<?php

namespace App\Http\Resources\Lawyer;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DecisionOrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'type_order' => __('typeOrder.'.$this->type_order->value),
            'status_order' => __('status.'.$this->status_order->value),
            'response_date' => $this->response_date ? $this->response_date->toDateString() : null,
            'created_at' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'decision' => DecisionResource::make($this->decision),
        ];
    }
}
