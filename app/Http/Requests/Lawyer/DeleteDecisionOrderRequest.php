<?php

namespace App\Http\Requests\Lawyer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class DeleteDecisionOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        $order_id  =  $this->route('order_id');
        return Gate::allows('checkOrderForYou', $order_id);
    }


    public function rules(): array
    {
        return [
            // 'order_id' => 'required|exists:decision_orders,id'
        ];
    }
}
