<?php

namespace App\Http\Requests\Employee;

use App\Models\Dewan;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class InterestEmployeeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return Gate::allows('IsEmployeeInCourtCase',[$this->interest_id]);
    }


    public function rules(): array
    {
        return [
            'interest_id' => 'required',
        ];
    }
}
