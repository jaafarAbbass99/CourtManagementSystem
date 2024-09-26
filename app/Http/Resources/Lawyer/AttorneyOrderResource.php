<?php

namespace App\Http\Resources\Lawyer;

use App\Http\Resources\ProfileUserResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttorneyOrderResource extends JsonResource
{

    public function toArray(Request $request): array
    {

        return [
            'id_order' => $this->id,
            'status_order' => __('status.'.$this->status_order->value),
            'date_order' => $this->created_at->toDateTimeString(),
            'updated_at' => $this->updated_at->toDateTimeString(),
            'requester' => new UserResource($this->whenLoaded('requester'))
        ];
    }
}
