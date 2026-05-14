<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    /**
     * GET /api/barang
     */
    public function index()
    {
        $data = Barang::orderByRaw("
            CAST(SUBSTRING_INDEX(KODE, '_', -1) AS UNSIGNED)
        ")->get();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diambil',
            'data' => $data
        ], 200);
    }

    /**
     * POST /api/barang
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NAMA' => 'required|string|max:255',
            'KATEGORI' => 'required|string|max:255',
            'HARGA' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $lastNumber = Barang::selectRaw("
            MAX(CAST(SUBSTRING_INDEX(KODE, '_', -1) AS UNSIGNED)) as max_id
        ")->value('max_id');

        $newId = 'BRG_' . (($lastNumber ?? 0) + 1);

        $barang = Barang::create([
            'KODE' => $newId,
            'NAMA' => $request->NAMA,
            'KATEGORI' => $request->KATEGORI,
            'HARGA' => $request->HARGA,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil ditambahkan',
            'data' => $barang
        ], 201);
    }

    /**
     * GET /api/barang/{id}
     */
    public function show(string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diambil',
            'data' => $barang
        ], 200);
    }

    /**
     * PUT /api/barang/{id}
     */
    public function update(Request $request, string $id)
    {

        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'NAMA' => 'sometimes|string|max:255',
            'KATEGORI' => 'sometimes|string|max:255',
            'HARGA' => 'sometimes|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $barang->update($request->only([
            'NAMA',
            'KATEGORI',
            'HARGA'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil diupdate',
            'data' => $barang
        ], 200);
    }

    /**
     * DELETE /api/barang/{id}
     */
    public function destroy(string $id)
    {
        $barang = Barang::find($id);

        if (!$barang) {
            return response()->json([
                'success' => false,
                'message' => 'Data barang tidak ditemukan',
            ], 404);
        }

        $barang->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data barang berhasil dihapus',
        ], 200);
    }
}
