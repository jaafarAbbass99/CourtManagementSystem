<?php

namespace App\Http\Resources\Admin\ProcessingAuth;

use App\Enums\Status;
use App\Http\Resources\AccountUserResource;
use App\Http\Resources\ProfileUserResource;
use App\Models\Account;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GetLawyerWithRoleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'profile' => ProfileUserResource::make($this->user),
            'account' => AccountUserResource::make($this),
        ];
    }
}
