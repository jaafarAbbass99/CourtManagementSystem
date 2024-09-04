<?php

namespace App\Http\Requests\Admin;

use App\Models\JudgeSection;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class AddJudgeSectionRequest extends FormRequest
{

    public function authorize(): bool
    {
        //التحقق من ان المستخدم الذي يضاف هو قاضي 
        return User::where('id','user_id')->where('role',2)->exists();
    }

    public function rules(): array
    {
        return [
            'user_id' => 'required|exists:users,id',
            'section_id' => 'required|exists:sections,id',
            'court_id' => 'required|exists:courts,id',
            'role' =>  ['required', 'in:responsible,member', $this->uniqueResponsibleInSection()],
        ];
    }

    public function messages(): array
    {
        return [
            'user_id.required' => 'حقل المستخدم مطلوب.',
            'user_id.exists' => 'المستخدم غير موجود في النظام.',
            'section_id.required' => 'حقل القسم مطلوب.',
            'section_id.exists' => 'القسم غير موجود.',
            'court_id.required' => 'حقل المحكمة مطلوب.',
            'court_id.exists' => 'المحكمة غير موجودة.',
            'role.required' => 'حقل الدور مطلوب.',
            'role.in' => 'الدور يجب أن يكون إما مسؤول أو عضو.',
            'role.unique_responsible' => 'لا يمكن أن يكون هناك أكثر من مسؤول في نفس القسم.',
        ];
    }



    protected function uniqueResponsibleInSection(): \Closure
    {
        return function ($attribute, $value, $fail) {
            if ($value === 'responsible') {

                $existingResponsible = JudgeSection::where('section_id', $this->section_id)
                    ->where('role', 'responsible')
                    ->exists();

                if ($existingResponsible) {
                    $fail('لا يمكن أن يكون هناك أكثر من مسؤول في نفس القسم.');
                }
            }
        };
    }







}
