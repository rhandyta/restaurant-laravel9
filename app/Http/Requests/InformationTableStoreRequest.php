<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class InformationTableStoreRequest extends FormRequest
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
            'category_table_id' => 'required',
            'seating_capacity' => 'required|min:1',
            'available' => 'required',
            'location' => 'required|string'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status_code' => 400,
            'messages' => $validator->errors()
        ], 400));
    }

    public function attributes()
    {
        return [
            'category_table_id' => 'category table',
            'seating_capacity' => 'seating capacity',
            'available' => 'available',
            'location' => 'location'
        ];
    }
}
