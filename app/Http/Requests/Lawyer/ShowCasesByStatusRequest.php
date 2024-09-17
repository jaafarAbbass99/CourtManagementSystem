<?php

namespace App\Http\Requests\Lawyer;

use App\Enums\TypeCourt;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class ShowCasesByStatusRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }
    
    public function rules(): array
    {
        return [
            'court_id' =>  'required|exists:courts,id',
            'type_court' => ['required', new Enum(TypeCourt::class)],
            'status' => 'required',
        ];
    }
}
