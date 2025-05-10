<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;
use CloudinaryLabs\CloudinaryLaravel\Facades\Cloudinary;

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
            'content' => 'required|string',
            'image' => 'nullable|file|image',
            'tags' => 'nullable|array',
            'author_id' => 'required|string',
        ]);
        if ($request->hasFile('image')) {
            // Assuming Cloudinary is configured as a disk in config/filesystems.php
            $file = $request->file('image');
            $path = Storage::disk('cloudinary')->putFile('blogs', $file);
            $data['image'] = Cloudinary::getUrl($path);
        } elseif ($request->has('image')) {
            $data['image'] = $request->input('image');
        }
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
            'content' => 'sometimes|required|string',
            'image' => 'nullable|file|image',
            'tags' => 'nullable|array',
            'author_id' => 'sometimes|required|string',
        ]);
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $path = Storage::disk('cloudinary')->putFile('blogs', $file);
            $data['image'] = Cloudinary::getUrl($path);
        } elseif ($request->has('image')) {
            $data['image'] = $request->input('image');
        }
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