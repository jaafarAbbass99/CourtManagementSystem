<?php

namespace App\Http\Requests\Admin\ProcessingLawyerAuth;

use Illuminate\Foundation\Http\FormRequest;

class IdenDocumentIDRequest extends FormRequest
{
    
    public function authorize(): bool
    {
        return true;
    }

   
    public function rules(): array
    {
        return [
            'document_id' => 'required|exists:iden_docs,id',
        ];
    }
}
