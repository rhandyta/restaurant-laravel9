<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FoodListUpdateRequest extends FormRequest
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
            'food_category_id' => 'required|numeric',
            'food_name' => 'required|string|min:3|max:100',
            'food_description' => 'required|string|min:3',
            'price' => 'required|numeric',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status_code' => 422,
            'messages' => $validator->errors()
        ], 422));
    }

    public function attributes()
    {
        return [
            'food_category_id' => 'food category',
            'food_name' => 'food name',
            'food_description' => 'food description',
            'price' => 'price'
        ];
    }
}
