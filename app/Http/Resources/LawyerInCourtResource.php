<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class LawyerInCourtResource extends JsonResource
{

    public function toArray(Request $request): array
    {
        return [
            'user' => new UserResource($this->user),
            'court' => new CourtResource($this->court),
        ];
    }
}
