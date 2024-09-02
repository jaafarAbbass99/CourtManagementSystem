<?php

namespace App\Http\Requests\Auth;

use App\Enums\Gender;
use App\Enums\Role;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:'. implode(',', Gender::values()),
            'role' => 'required|in:'. implode(',', Role::values()),
            'phone_number' => 'required|string|max:20',
            'user_name' => 'required|string|max:255|unique:accounts,user_name',
            'email' => 'required|string|email|max:255|unique:accounts,email',
            'password' => ['required', 'confirmed'],
            'country' => 'required', 
            'city'=> 'required', 
            'street' => 'required'
        ];
    }
}
