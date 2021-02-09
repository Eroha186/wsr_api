<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'dispatch_from' => 'required',
            'dispatch_from.id' => 'required|exists:dispatches,id',
            'dispatch_from.date' => 'required|date_format:Y-m-d',
            'dispatch_back.id' => 'required_with:dispatch_from|exists:dispatches,id',
            'dispatch_back.date' => 'required_with:dispatch_from|date_format:Y-m-d',
            'passengers' => 'required|array|between:1,8',
            'passengers.*.first_name' => 'required',
            'passengers.*.last_name' => 'required',
            'passengers.*.birth_date' => 'required|date_format:Y-m-d',
            'passengers.*.document_number' => 'required|digits:10'

        ];
    }
}
