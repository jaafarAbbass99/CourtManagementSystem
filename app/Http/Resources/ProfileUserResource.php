<?php

namespace App\Http\Resources;

use App\Enums\Gender;
use App\Enums\Role;
use Generator;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileUserResource extends JsonResource
{
    
    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id ,
            "first_name" => $this->first_name,
            "last_name" => $this->last_name,
            "gender" => __('gender.' . $this->gender->value),
            "mobile_number" => $this->phone_number,
            "role" => __('role.' . $this->role->value), 
            "image" => $this->photo ,
        ];
    }
}
