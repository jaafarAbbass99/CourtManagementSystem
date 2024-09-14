<?php

namespace App\Http\Requests\Lawyer;

use Illuminate\Foundation\Http\FormRequest;

class ShowDetailsCaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_id' => 'required|exists:cases,id',
            'court_type_id' => 'required'
        ];
    }
}
