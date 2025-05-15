<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ContactController extends Controller
{
    public function index()
    {
        return response()->json(Contact::all());
    }

    public function show($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        return response()->json($contact);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'nullable|string',
            'message' => 'required|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        
        // Nếu không cung cấp thời gian, tự động thiết lập
        if (!isset($data['createdAt'])) {
            $data['createdAt'] = now();
        }
        if (!isset($data['updatedAt'])) {
            $data['updatedAt'] = now();
        }
        
        $contact = Contact::create($data);
        return response()->json($contact, 201);
    }

    public function update(Request $request, $id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'email' => 'sometimes|required|email',
            'phone' => 'nullable|string',
            'message' => 'sometimes|required|string',
            'createdAt' => 'nullable|date',
            'updatedAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        
        // Luôn cập nhật thời gian updatedAt khi cập nhật
        $data['updatedAt'] = now();
        
        $contact->update($data);
        return response()->json($contact);
    }

    public function destroy($id)
    {
        $contact = Contact::find($id);
        if (!$contact) {
            return response()->json(['message' => 'Contact not found'], 404);
        }
        $contact->delete();
        return response()->json(['message' => 'Contact deleted successfully']);
    }
}