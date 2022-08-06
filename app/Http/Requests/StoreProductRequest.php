<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
            'name' => 'required|string|min:3',
            'sku' => 'required|string|min:3',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:0|integer',
            'category_id' => 'exists:categories,id|nullable'
        ];
    }
}
