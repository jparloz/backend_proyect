<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCommentRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'rating' => 'required|integer|between:0,100',
            'comment' =>'required|min:20|max:300'
        ];
    }
}
