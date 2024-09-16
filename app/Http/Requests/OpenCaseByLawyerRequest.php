<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OpenCaseByLawyerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'my_court_id' => 'required|exists:courts,id',
            'party_one' => 'required|string',
            'party_two' => 'required|string',
            'subject' => 'required|string',
            'case_type_id' => 'required|exists:case_types,id',
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png', 
            'summary' => 'required|string|max:255',
        ];
    }
}
