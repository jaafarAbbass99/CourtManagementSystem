<?php

namespace App\Http\Requests\User\Add;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class AddDefenseOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return Gate::denies('isLawyerTakeCase',[$this->case_id,$this->lawyer_id]);
    }

    public function rules(): array
    {
        return [
            'case_id' => 'required',
            'lawyer_id' => 'required|exists:users,id'
        ];
    }
}
