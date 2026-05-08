<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenjualanController extends Controller
{
    /**
     * GET /api/penjualan
     */
    public function index()
    {
        $data = Penjualan::all();

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil diambil',
            'data' => $data
        ], 200);
    }

    /**
     * POST /api/penjualan
     */
    public function store(Request $request)
    {
        $data = Penjualan::all();

        $validator = Validator::make($request->all(), [
            "TGL" => "required|date",
            "KODE_PELANGGAN" => "required|exists:pelanggan,ID_PELANGGAN",
            "SUBTOTAL" => "required|integer",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $lastNumber = Penjualan::selectRaw("
            MAX(CAST(SUBSTRING_INDEX(ID_NOTA, '_', -1) AS UNSIGNED)) as max_id
        ")->value('max_id');

        $newId = 'NOTA_' . (($lastNumber ?? 0) + 1);

        $penjualan = Penjualan::create([
            'ID_NOTA' => $newId,
            'TGL' => $request->TGL,
            'KODE_PELANGGAN' => $request->KODE_PELANGGAN,
            'SUBTOTAL' => $request->SUBTOTAL,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil ditambahkan',
            'data' => $penjualan
        ], 201);
    }

    /**
     * GET /api/penjualan/{id}
     */
    public function show(string $id)
    {
        $data = Penjualan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil diambil',
            'data' => $data
        ], 200);
    }

    /**
     * PATCH /api/penjualan/{id}
     */
    public function update(Request $request, string $id)
    {
        $data = Penjualan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            "TGL" => "sometimes|date",
            "KODE_PELANGGAN" => "sometimes|exists:pelanggan,ID_PELANGGAN",
            "SUBTOTAL" => "sometimes|integer",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $data->update([
            'TGL' => $request->TGL ?? $data->TGL,
            'KODE_PELANGGAN' => $request->KODE_PELANGGAN ?? $data->KODE_PELANGGAN,
            'SUBTOTAL' => $request->SUBTOTAL ?? $data->SUBTOTAL,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil diupdate',
            'data' => $data
        ], 200);
    }

    /**
     * DELETE /api/penjualan/{id}
     */
    public function destroy(string $id)
    {
        $data = Penjualan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data penjualan tidak ditemukan',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data penjualan berhasil dihapus',
        ], 200);
    }
}
