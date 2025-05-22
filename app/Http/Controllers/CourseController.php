<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        return response()->json(Course::all());
    }

    public function show($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        return response()->json($course);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'thumbnail' => 'nullable|string',
            'instructor' => 'required|string',
            'instructorRole' => 'nullable|string',
            'instructorDescription' => 'nullable|string',
            'name' => 'required|string',
            'description' => 'nullable|string',
            'duration' => 'required|numeric',
            'students' => 'nullable|integer',
            'price' => 'required|integer',
            'discount' => 'nullable|integer',
            'courseModules' => 'nullable|array',
            'category' => 'nullable|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $course = Course::create($data);
        return response()->json($course, 201);
    }

    public function update(Request $request, $id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        $data = $request->validate([
            'thumbnail' => 'nullable|string',
            'instructor' => 'sometimes|required|string',
            'instructorRole' => 'nullable|string',
            'instructorDescription' => 'nullable|string',
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'duration' => 'sometimes|required|numeric',
            'students' => 'nullable|integer',
            'price' => 'sometimes|required|integer',
            'discount' => 'nullable|integer',
            'courseModules' => 'nullable|array',
            'category' => 'nullable|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $course->update($data);
        return response()->json($course);
    }

    public function destroy($id)
    {
        $course = Course::find($id);
        if (!$course) {
            return response()->json(['message' => 'Course not found'], 404);
        }
        $course->delete();
        return response()->json(['message' => 'Course deleted successfully']);
    }
}