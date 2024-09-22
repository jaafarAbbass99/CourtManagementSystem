<?php

namespace App\Http\Requests\Lawyer;

use App\Enums\TypeOrder;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\Rules\Enum;

class StoreDecisionOrderRequest extends FormRequest
{

    public function authorize(): bool
    {
        $decision_id  =  $this->input('decision_id');
        return Gate::allows('checkEndDecision' , $decision_id);
    }

    public function rules()
    {
        return [
            'decision_id' => 'required|exists:decisions,id|unique:decision_orders,decision_id',
            'type_order' => ['required', new Enum(TypeOrder::class)],
        ];
    }

 
}
