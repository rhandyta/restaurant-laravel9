<?php

namespace App\Http\Requests\API\Order;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class OrderRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'detail_orders' => ['required', 'array'],
            'tables' => ['required', 'string'],
            'table' => ['required', 'string']
        ];
    }

    public function attributes()
    {
        return [
            'detail_orders' => 'detail orders',
            'tables' => "table category",
            'table' => 'table number'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status_code' => 422,
            'messages' => $validator->errors()
        ], 422));
    }
}
