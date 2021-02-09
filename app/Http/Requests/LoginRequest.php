<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'phone' => 'required',
            'password' => 'required'
        ];
    }
}
