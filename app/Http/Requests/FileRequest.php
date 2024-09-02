<?php

namespace App\Http\Requests;

use App\Enums\Status;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Http\FormRequest;

class FileRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        $req_doc = $this->input('req_doc');
        
        $role = $this->user()->user->role;

        return Gate::allows('checkReqDoc',[$role,$req_doc]);
    }

    
    public function rules(): array
    {
        return [
            'file' => 'required|file|mimes:pdf,jpeg,png,jpg',
            'req_doc' => 'required|in:'.implode(',', Status::values())
        ];
    }

    protected function failedAuthorization()
    {
        throw new AuthorizationException();
        
    }
}
