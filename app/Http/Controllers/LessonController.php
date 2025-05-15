<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        return response()->json(Lesson::all());
    }

    public function show($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
        return response()->json($lesson);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'video_url' => 'required|string',
            'courseId' => 'required|string',
            'moduleId' => 'required|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $lesson = Lesson::create($data);
        return response()->json($lesson, 201);
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'video_url' => 'sometimes|required|string',
            'courseId' => 'sometimes|required|string',
            'moduleId' => 'sometimes|required|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $lesson->update($data);
        return response()->json($lesson);
    }

    public function destroy($id)
    {
        $lesson = Lesson::find($id);
        if (!$lesson) {
            return response()->json(['message' => 'Lesson not found'], 404);
        }
        $lesson->delete();
        return response()->json(['message' => 'Lesson deleted successfully']);
    }
}