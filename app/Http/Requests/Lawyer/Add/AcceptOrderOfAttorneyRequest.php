<?php

namespace App\Http\Requests\Lawyer\Add;

use App\Enums\Status;
use App\Models\order;
use Illuminate\Foundation\Http\FormRequest;

class AcceptOrderOfAttorneyRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            //
        ];
    }
}
