<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SeatRequest extends FormRequest
{
    public function rules()
    {
        return [
            'passenger' => 'required|exists:passengers,id',
            'seat' => 'required|integer|between:1,60',
            'type' => 'required|in:from,back'
        ];
    }
}
