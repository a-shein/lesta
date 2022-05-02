<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetFibonacciRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'from' => 'numeric|min:0',
            'to' => 'requiredWith:from|numeric|gte:from',
        ];
    }
}
