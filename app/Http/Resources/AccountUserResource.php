<?php

namespace App\Http\Resources;

use App\Enums\Status;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AccountUserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            "id"=> $this->id ,
            "user_name" =>  $this->user_name,
            "email" => $this->email,
            "status" => __('status.'.$this->status),
        ];
    }
}
