<?php

namespace App\Http\Requests\API\Cart;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CartStoreRequest extends FormRequest
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
        $data = request()->all();
        return [
            'user_id' => ['required', 'numeric'],
            'product_id' => ['required', 'numeric'],
            'quantity' => ['required', 'numeric', "not_in:0"]
        ];
    }

    public function attributes()
    {
        return [
            'user_id' => 'user',
            'product_id' => 'product'
        ];
    }
}
