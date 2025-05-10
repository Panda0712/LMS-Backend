<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'user_id' => 'required|string',
            'user_name' => 'required|string|min:5',
            'user_email' => 'required|email',
            'phone' => 'required|string',
            'amount' => 'required|numeric',
            'payment_method' => 'required|string|in:momo,zalopay',
            'order_id' => 'required|string',
        ];
    }
}