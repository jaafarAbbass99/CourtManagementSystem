<?php

namespace App\Http\Requests\User\Add;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class OkAttorneyOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('isAttorneyOrderForMe',[$this->order_id]);
    }


    public function rules(): array
    {
        return [
            'order_id'=> 'required',
        ];
    }
}
