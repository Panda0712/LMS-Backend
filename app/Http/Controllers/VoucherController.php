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
            'code' => 'required|string',
            'discount_type' => 'required|string',
            'discount_value' => 'required|numeric',
            'usage_limit' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
        ]);
        $voucher = Voucher::create($data);
        return response()->json($voucher, 201);
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::find($id);
        if (!$voucher) {
            return response()->json(['message' => 'Voucher not found'], 404);
        }
        $data = $request->validate([
            'code' => 'sometimes|required|string',
            'discount_type' => 'sometimes|required|string',
            'discount_value' => 'sometimes|required|numeric',
            'usage_limit' => 'nullable|integer',
            'used_count' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'valid_until' => 'nullable|date',
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