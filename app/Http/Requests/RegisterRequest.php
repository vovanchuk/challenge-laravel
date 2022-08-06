<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'email' => 'required|string|email|unique:users,email',
            'name' => 'required|string|min:3',
            'password' => 'required|string|min:8|confirmed'
        ];
    }
}
