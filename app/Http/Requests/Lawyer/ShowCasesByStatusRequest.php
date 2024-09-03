<?php

namespace App\Http\Requests\Lawyer;

use Illuminate\Foundation\Http\FormRequest;

class ShowCasesByStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'my_court_id' => 'required|exists:lawyer_courts,id',
            'status' => 'required',
        ];
    }
}
