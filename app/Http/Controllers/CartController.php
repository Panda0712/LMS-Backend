<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use \MongoDB\BSON\ObjectId; 
use Illuminate\Http\Response;

class CartController extends Controller
{
    public function index()
    {
        return response()->json(Cart::all());
    }

    public function show($id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        return response()->json($cart);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'userId' => 'required|string',
            'courseId' => 'required|string',
            'totalPrice' => 'required|integer',
            'courseName' => 'required|string',
            'courseThumbnail' => 'nullable|string',
            'instructor' => 'nullable|string',
            'duration' => 'nullable|numeric',
            'totalLessons' => 'nullable|integer',
            'totalReviews' => 'nullable|integer',
            'rating' => 'nullable|integer',
        ]);
        
        $data['createdAt'] = now();
        $data['updatedAt'] = now();
        $data['_destroy'] = false;
        
        $cart = Cart::create($data);
        return response()->json($cart, 201);
    }

    public function update(Request $request, $id)
    {
        $cart = Cart::find($id);
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $data = $request->validate([
            'userId' => 'sometimes|required|string',
            'courseId' => 'sometimes|required|string',
            'totalPrice' => 'sometimes|required|integer',
            'courseName' => 'sometimes|required|string',
            'courseThumbnail' => 'nullable|string',
            'instructor' => 'nullable|string',
            'duration' => 'nullable|numeric',
            'totalLessons' => 'nullable|integer',
            'totalReviews' => 'nullable|integer',
            'rating' => 'nullable|integer',
        ]);
        
        $data['updatedAt'] = now();
        
        $cart->update($data);
        return response()->json($cart);
    }

    public function destroy($id)
    {
        $cart = Cart::where('_id', $id)->first();
        if (!$cart) {
            return response()->json(['message' => 'Cart not found'], 404);
        }
        $cart->delete();
        return response()->json(['message' => 'Cart deleted successfully']);
    }

    public function findByUserAndCourse(Request $request)
    {
        $userId = $request->query('userId');
        $courseId = $request->query('courseId');
        
        if (!$userId || !$courseId) {
            return response()->json(['message' => 'userId and courseId are required'], 400);
        }
        
        $cart = Cart::where('userId', $userId)->where('courseId', $courseId)->first();
        if (!$cart) {
            return response()->json(['exists' => false, 'message' => 'Cart not found'], 404);
        }
        
        return response()->json(['exists' => true, 'data' => $cart]);
    }
}