<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'name' => 'required',
            'email' => 'required|email|regex:/^.+@.+\..+$/i|unique:users',
            'password' => 'required|min:6'
        ];
    }
}
