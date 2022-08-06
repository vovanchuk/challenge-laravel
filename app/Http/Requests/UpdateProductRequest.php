<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'name' => 'string|min:3',
            'sku' => 'string|min:3',
            'price' => 'numeric|min:0',
            'quantity' => 'numeric|min:0|integer',
            'category_id' => 'exists:categories,id|nullable'
        ];
    }
}
