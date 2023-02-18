<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class FoodCategoryUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function rules()
    {
        return [
            'category_name' => 'required|min:2|max:100',
            'category_description' => 'required|min:5'
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
            'category_name' => 'category name',
            'category_description' => "category description"
        ];
    }
}
