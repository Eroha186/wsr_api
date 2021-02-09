<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DispatchRequest extends BaseRequest
{

    public function rules()
    {
        return [
            'from' => 'required|exists:stations,id',
            'to' => 'required|exists:stations,id',
            'date1' => 'required|date_format:Y-m-d',
            'date2' => 'required|date_format:Y-m-d',
            'passengers' => 'required|integer|between:1,4',
        ];
    }
}
