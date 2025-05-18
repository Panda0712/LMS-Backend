<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class OrderController extends Controller
{
    public function index()
    {
        return response()->json(Order::all());
    }

    public function show($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        return response()->json($order);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'userId' => 'required|string',
            'courseId' => 'required|string',
            'userEmail' => 'required|string',
            'userName' => 'required|string',
            'courseName' => 'required|string',
            'courseThumbnail' => 'nullable|string',
            'instructor' => 'nullable|string',
            'totalPrice' => 'required|integer',
            'paymentMethod' => 'nullable|string',
            'status' => 'nullable|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $order = Order::create($data);
        return response()->json($order, 201);
    }

    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $data = $request->validate([
            'userId' => 'sometimes|required|string',
            'courseId' => 'sometimes|required|string',
            'userEmail' => 'sometimes|required|string',
            'userName' => 'sometimes|required|string',
            'courseName' => 'sometimes|required|string',
            'courseThumbnail' => 'nullable|string',
            'instructor' => 'nullable|string',
            'totalPrice' => 'sometimes|required|integer',
            'paymentMethod' => 'nullable|string',
            'status' => 'nullable|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $order->update($data);
        return response()->json($order);
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if (!$order) {
            return response()->json(['message' => 'Order not found'], 404);
        }
        $order->delete();
        return response()->json(['message' => 'Order deleted successfully']);
    }
}