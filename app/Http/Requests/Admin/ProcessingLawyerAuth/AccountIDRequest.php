<?php

namespace App\Http\Requests\Admin\ProcessingLawyerAuth;

use Illuminate\Foundation\Http\FormRequest;

class AccountIDRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    
    public function rules(): array
    {
        return [
            'account_id' => 'required|exists:accounts,id',
        ];
    }
}
