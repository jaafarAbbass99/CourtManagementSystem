<?php

namespace App\Http\Requests\User\Show;

use Illuminate\Foundation\Http\FormRequest;

class ShowCaseUserRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'case_number' => 'required',
            'case_type' => 'required|string',
            'case_year' => 'required|integer',
            'case_base_number' => 'required',
            'case_court_type_id' => 'required|exists:court_types,id',
        ];
    }
}
