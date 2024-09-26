<?php

namespace App\Http\Requests\User\Add;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class cancelAttorneyOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('isAttorneyOrderForMeAndAbleCancel',[$this->order_id]);   
    }

    public function rules(): array
    {
        return [
            'order_id'=> 'required'
        ];
    }
}
