<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PaymentRequest;

class PaymentController extends Controller
{
    public function pay(PaymentRequest $request)
    {
        $method = $request->payment_method;
        if ($method === 'momo') {
            // Momo payment logic here
            return response()->json(['message' => 'Momo payment processed'], 200);
        } elseif ($method === 'zalopay') {
            // ZaloPay payment logic here
            return response()->json(['message' => 'ZaloPay payment processed'], 200);
        }
        return response()->json(['message' => 'Invalid payment method'], 400);
    }

    public function callback(Request $request)
    {
        // Handle payment gateway callback logic here
        return response()->json(['message' => 'Payment callback received'], 200);
    }
}