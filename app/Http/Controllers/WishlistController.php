<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WishlistController extends Controller
{
    public function index()
    {
        return response()->json(Wishlist::all());
    }

    public function show($id)
    {
        $wishlist = Wishlist::find($id);
        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist not found'], 404);
        }
        return response()->json($wishlist);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'userId' => 'required|string',
            'courseId' => 'required|string',
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
        
        $wishlist = Wishlist::create($data);
        return response()->json($wishlist, 201);
    }

    public function update(Request $request, $id)
    {
        $wishlist = Wishlist::find($id);
        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist not found'], 404);
        }
        $data = $request->validate([
            'userId' => 'sometimes|required|string',
            'courseId' => 'sometimes|required|string',
            'courseName' => 'sometimes|required|string',
            'courseThumbnail' => 'nullable|string',
            'instructor' => 'nullable|string',
            'duration' => 'nullable|numeric',
            'totalLessons' => 'nullable|integer',
            'totalReviews' => 'nullable|integer',
            'rating' => 'nullable|integer',
        ]);
        
        $data['updatedAt'] = now();
        
        $wishlist->update($data);
        return response()->json($wishlist);
    }

    public function destroy($id)
    {
        $wishlist = Wishlist::where('_id', $id)->first();
        if (!$wishlist) {
            return response()->json(['message' => 'Wishlist not found'], 404);
        }
        $wishlist->delete();
        return response()->json(['message' => 'Wishlist deleted successfully']);
    }

    public function findByUserAndCourse(Request $request)
    {
        $userId = $request->query('userId');
        $courseId = $request->query('courseId');
        
        if (!$userId || !$courseId) {
            return response()->json(['message' => 'userId and courseId are required'], 400);
        }
        
        $wishlist = Wishlist::where('userId', $userId)->where('courseId', $courseId)->first();
        if (!$wishlist) {
            return response()->json(['exists' => false, 'message' => 'Wishlist not found'], 404);
        }
        
        return response()->json(['exists' => true, 'data' => $wishlist]);
    }
}