<?php

namespace App\Http\Controllers;

use App\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ModuleController extends Controller
{
    public function index()
    {
        return response()->json(Module::all());
    }

    public function show($id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }
        return response()->json($module);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
            'course_id' => 'required|string',
            'lessonIds' => 'nullable|array',
            'order' => 'nullable|integer',
        ]);
        $module = Module::create($data);
        return response()->json($module, 201);
    }

    public function update(Request $request, $id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }
        $data = $request->validate([
            'title' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'course_id' => 'sometimes|required|string',
            'lessonIds' => 'nullable|array',
            'order' => 'nullable|integer',
        ]);
        $module->update($data);
        return response()->json($module);
    }

    public function destroy($id)
    {
        $module = Module::find($id);
        if (!$module) {
            return response()->json(['message' => 'Module not found'], 404);
        }
        $module->delete();
        return response()->json(['message' => 'Module deleted successfully']);
    }
}