<?php

namespace App\Http\Requests\User\Add;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class cancelInterestedCaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('isInterestMe',$this->interest_id);
    }


    public function rules(): array
    {
        return [
            
        ];
    }
}
