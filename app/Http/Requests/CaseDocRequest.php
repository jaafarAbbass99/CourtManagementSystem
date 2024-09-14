<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseDocRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,doc,docx,jpg,png|max:2048', 
            'summary' => 'required|string|max:255',
            'type' => 'required|string|max:50',
            'court_type_id' => 'required|exists:court_types,id',
            'case_id' => 'required|exists:cases,id',  
        ];
    }
}
