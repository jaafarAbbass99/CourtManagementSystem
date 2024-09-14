<?php

namespace App\Http\Resources\Judge\Show;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'id' => $this->user->id,
            'first_name' => $this->user->first_name,
            'last_name' => $this->user->last_name,
            'representing' => $this->pivot->representing,
        ];
    }
}
