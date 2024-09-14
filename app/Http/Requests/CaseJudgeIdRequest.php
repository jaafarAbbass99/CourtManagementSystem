<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CaseJudgeIdRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'j_case_id' => 'required|exists:case_judges,id'
        ];
    }
}
