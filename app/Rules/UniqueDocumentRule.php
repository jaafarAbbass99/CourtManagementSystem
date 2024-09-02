<?php

namespace App\Rules;

use App\Models\Document;
use Closure;
use Illuminate\Contracts\Validation\Rule;

class UniqueDocumentRule implements Rule
{
    public function passes($attribute,$value)
    {
        $userId = request()->user()->id;

        return !Document::where('user_id', $userId)
                        ->where('document_type', $value) // $value هو document_type الممرر
                        ->exists();

    }
    public function message()
    {
        return 'This document type has already been uploaded by the user.';
    }


}
