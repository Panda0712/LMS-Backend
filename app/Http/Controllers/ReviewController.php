<?php

namespace App\Http\Controllers;

use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReviewController extends Controller
{
    public function index()
    {
        return response()->json(Review::all());
    }

    public function show($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        return response()->json($review);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'userId' => 'required|string',
            'courseId' => 'required|string',
            'rating' => 'required|numeric',
            'content' => 'nullable|string',
            'userAvatar' => 'nullable|string',
            'userName' => 'nullable|string',
        ]);
        $data['createdAt'] = now();
        $data['updatedAt'] = now();
        $data['_destroy'] = false;
        $review = Review::create($data);
        return response()->json($review, 201);
    }

    public function update(Request $request, $id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        $data = $request->validate([
            'userId' => 'sometimes|required|string',
            'courseId' => 'sometimes|required|string',
            'rating' => 'sometimes|required|numeric',
            'content' => 'nullable|string',
            'userAvatar' => 'nullable|string',
            'userName' => 'nullable|string',
            '_destroy' => 'nullable|boolean',
        ]);
        $data['updatedAt'] = now();
        $review->update($data);
        return response()->json($review);
    }

    public function destroy($id)
    {
        $review = Review::find($id);
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
        $review->delete();
        return response()->json(['message' => 'Review deleted successfully']);
    }
}