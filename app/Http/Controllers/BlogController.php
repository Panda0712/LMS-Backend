<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BlogController extends Controller
{
    public function index()
    {
        return response()->json(Blog::all());
    }

    public function show($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        return response()->json($blog);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'summary' => 'nullable|string',
            'content' => 'required|string',
            'tags' => 'nullable|array',
            'coverImage' => 'nullable|string',
            'author' => 'nullable|string',
            'authorId' => 'required|string',
        ]);
        
        $data['createdAt'] = now();
        $data['updatedAt'] = now();
        $data['_destroy'] = false;
        
        $blog = Blog::create($data);
        return response()->json($blog, 201);
    }

    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'summary' => 'nullable|string',
            'content' => 'sometimes|required|string',
            'tags' => 'nullable|array',
            'coverImage' => 'nullable|string',
            'author' => 'nullable|string',
            'authorId' => 'sometimes|required|string',
        ]);
        
        $data['updatedAt'] = now();
        
        $blog->update($data);
        return response()->json($blog);
    }

    public function destroy($id)
    {
        $blog = Blog::find($id);
        if (!$blog) {
            return response()->json(['message' => 'Blog not found'], 404);
        }
        $blog->delete();
        return response()->json(['message' => 'Blog deleted successfully']);
    }
}