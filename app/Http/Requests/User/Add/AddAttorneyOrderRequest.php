<?php

namespace App\Http\Requests\User\Add;

use Illuminate\Foundation\Http\FormRequest;

class AddAttorneyOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'lawyer_id' => 'required|exists:users,id',
            'court_id' => 'required',
        ];
    }
}
