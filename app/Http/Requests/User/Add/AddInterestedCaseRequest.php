<?php

namespace App\Http\Requests\User\Add;

use Illuminate\Foundation\Http\FormRequest;

class AddInterestedCaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'case_id'=> 'required|exists:cases,id',
            'party' => 'required'
        ];
    }
}
