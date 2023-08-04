<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankStoreUpdateRequest extends FormRequest
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
            'payment_type_id' => 'required',
            'name' => ['required', 'string', 'unique:banks,name'],
            'status' => ['required', 'string']
        ];
    }


    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status_code' => 422,
            'messages' => $validator->errors()
        ],422));
    }

    public function attributes()
    {
        return [
            'payment_type_id' => 'payment method',
        ];
    }
}
