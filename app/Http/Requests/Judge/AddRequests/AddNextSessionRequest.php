<?php

namespace App\Http\Requests\Judge\AddRequests;

use App\Enums\SessionType;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class AddNextSessionRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'case_id' => 'required|integer|exists:cases,id', 
            'session_date' => 'required|date|after_or_equal:'.Carbon::now()->addMonth()->format('Y-m-d') , // يجب أن يكون تاريخ الجلسة تاريخًا صالحًا ويبدأ من اليوم أو بعده
            'session_time' => 'required|date_format:H:i', // يجب أن يكون الوقت بتنسيق الساعة والدقيقة (مثال: 13:00)
            'session_type' => ['required', new Enum(SessionType::class)]
        ];
    }

    public function messages(): array
    {
        return [
            'case_id.required' => 'يجب إدخال معرف القضية.',
            'case_id.integer' => 'معرف القضية يجب أن يكون عددًا صحيحًا.',
            'case_id.exists' => 'القضية المختارة غير موجودة.',

            'session_date.required' => 'يجب إدخال تاريخ الجلسة.',
            'session_date.date' => 'تاريخ الجلسة يجب أن يكون بتنسيق صحيح.',
            'session_date.after_or_equal' => 'تاريخ الجلسة يجب أن يكون بعد شهر من اليوم.',


            'session_time.required' => 'يجب إدخال وقت الجلسة.',
            'session_time.date_format' => 'وقت الجلسة يجب أن يكون بتنسيق صحيح (HH:MM).',

            'session_type.required' => 'يجب تحديد نوع الجلسة.',
            'session_type.in' => 'نوع الجلسة غير صالح، يرجى اختيار نوع صحيح.',
        ];
    }
}
