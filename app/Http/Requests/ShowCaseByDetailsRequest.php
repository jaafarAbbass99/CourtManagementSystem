<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ShowCaseByDetailsRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'my_court_id' => 'required|exists:lawyer_courts,id',
            'party_one' => 'required|string',
            'year' => 'required|integer',
        ];
    }
}
