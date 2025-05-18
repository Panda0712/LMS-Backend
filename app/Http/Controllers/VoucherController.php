<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class VoucherController extends Controller
{
    public function index()
    {
        return response()->json(Voucher::all());
    }

    public function show($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
        return response()->json($voucher);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'discount' => 'required|integer',
            'courseIds' => 'nullable|array',
            'code' => 'required|string',
            'usageLimit' => 'nullable|integer',
            'usedCount' => 'nullable|integer',
            'minOrderValue' => 'nullable|integer',
            'expiredAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $voucher = Voucher::create($data);
        return response()->json($voucher, 201);
    }

    public function findByCode(Request $request)
    {
        $code = $request->query('code');
        $courseId = (string) $request->query('courseId');
    
        if (!$code) {
            return response()->json(['message' => 'Code parameter is required'], 400);
        }
        if (!$courseId) {
            return response()->json(['message' => 'CourseId parameter is required'], 400);
        }
    
        $voucher = Voucher::where('code', $code)->first();
    
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
    
        $courseIds = json_decode($voucher->courseIds, true);
    
        if (!is_array($courseIds) || !in_array($courseId, $courseIds)) {
            return response()->json(['message' => 'Voucher not valid for this course'], 404);
        }
    
        return response()->json($voucher);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
        $data = $request->validate([
            'name' => 'sometimes|required|string',
            'discount' => 'sometimes|required|integer',
            'courseIds' => 'nullable|array',
            'code' => 'sometimes|required|string',
            'usageLimit' => 'nullable|integer',
            'usedCount' => 'nullable|integer',
            'minOrderValue' => 'nullable|integer',
            'expiredAt' => 'nullable|date',
            '_destroy' => 'nullable|boolean',
        ]);
        $voucher->update($data);
        return response()->json($voucher);
    }

    public function destroy($id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
        $voucher->delete();
        return response()->json(['message' => 'Voucher deleted successfully']);
    }
}