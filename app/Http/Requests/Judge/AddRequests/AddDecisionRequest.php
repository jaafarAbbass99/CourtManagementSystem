<?php

namespace App\Http\Requests\Judge\AddRequests;

use Illuminate\Foundation\Http\FormRequest;

class AddDecisionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'session_id' => 'required|exists:sessions,id', // يجب أن يكون معرف الجلسة موجودًا في جدول الجلسات
            'summary' => 'required|string|max:500', // خلاصة القرار مطلوبة، نص، ولا تتجاوز 500 حرف
            'status' => 'required|in:ابتدائي,نهائي', // الحالة يجب أن تكون ضمن الخيارات المحددة
            'favor' => 'required|string|max:255', // لصالح أي طرف، نص، ويمكنك تحديد الخيارات وفقًا للحاجة
            'case_id' => 'required|exists:cases,id', // يجب أن يكون معرف القضية موجودًا في جدول القضايا
        ];
    }

    public function messages(): array
    {
        return [
            'session_id.required' => 'يجب تحديد الجلسة.',
            'session_id.exists' => 'الجلسة المحددة غير موجودة.',
            'summary.required' => 'يجب إدخال ملخص القرار.',
            'summary.string' => 'يجب أن يكون ملخص القرار نصًا.',
            'summary.max' => 'يجب ألا يتجاوز ملخص القرار 500 حرف.',
            'status.required' => 'يجب تحديد حالة القرار.',
            'status.in' => 'الحالة يجب أن تكون إما معلق، موافق عليه، أو مرفوض.',
            'favor.required' => 'يجب تحديد لصالح أي طرف.',
            'favor.string' => 'يجب أن يكون لصالح أي طرف نصًا.',
            'favor.max' => 'يجب ألا يتجاوز النص لصالح أي طرف 255 حرف.',
            'case_id.required' => 'يجب تحديد القضية.',
            'case_id.exists' => 'القضية المحددة غير موجودة.',
        ];
    }
}
