<?php

namespace App\Http\Requests;

use App\Enums\TypeCaseDoc;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class AddFilesToCaseRequest extends FormRequest
{

    public function authorize(): bool
    {
        // تحقق اذا كان الدعوى للمحامي + الدعوى في القسم المطلوب
        
        $case_id  =  $this->input('case_id');
        return Gate::allows('isCaseForLawyer' , $case_id);

    }


    public function rules(): array
    {
        return [
            'files' => 'required|array',
            'files.*' => 'file|mimes:jpg,png,pdf,docx|max:2048',
            'summaries' => 'required|array',
            'summaries.*' => 'string',
            'case_id' => 'required',
            'court_type_id' => 'required' ,
            'case_judge_id' => 'required',
            'type_docs' => 'nullable|string|in:'.TypeCaseDoc::DALIL->value,
        ];
    }
}
