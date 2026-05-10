<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ItemPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class ItemPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = ItemPenjualan::all();

        return response()->json([
            'success' => true,
            'message' => 'Data item penjualan berhasil diambil',
            'data' => $data
        ], 200);
    }

    public function getByNota(String $nota)
    {
        $data = ItemPenjualan::where('NOTA', $nota)->get();

        if ($data->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Data item penjualan tidak ditemukan untuk NOTA: ' . $nota,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data item penjualan berhasil diambil untuk NOTA: ' . $nota,
            'data' => $data
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NOTA' => 'required|exists:penjualan,ID_NOTA',
            'KODE_BARANG' => 'required|exists:barang,KODE',
            'QTY' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validasi gagal",
                "errors" => $validator->errors()
            ], 422);
        }

        $itemPenjualan = ItemPenjualan::create([
            "NOTA" => $request->NOTA,
            "KODE_BARANG" => $request->KODE_BARANG,
            "QTY" => $request->QTY
        ]);

        return response()->json([
            "success" => true,
            "message" => "Data item penjualan berhasil dibuat",
            "data" => $itemPenjualan
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data = ItemPenjualan::find($id);
        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data item penjualan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data item penjualan berhasil diambil',
            'data' => $data
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data = ItemPenjualan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data item penjualan tidak ditemukan',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'NOTA' => 'required|exists:penjualan,ID_NOTA',
            'KODE_BARANG' => 'required|exists:barang,KODE',
            'QTY' => 'required|integer|min:1',
        ]);

        if (!$validator->fails()) {
            return response()->json([
                "success" => false,
                "message" => "Validasi gagal",
                "errors" => $validator->errors()
            ], 422);
        }

        $data->update([
            "NOTA" => $request->NOTA ?? $data->NOTA,
            "KODE_BARANG" => $request->KODE_BARANG ?? $data->KODE_BARANG,
            "QTY" => $request->QTY ?? $data->QTY
        ]);

        return response()->json([
            "success" => true,
            "message" => "Data item penjualan berhasil diupdate",
            "data" => $data
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $data = ItemPenjualan::find($id);

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => 'Data item penjualan tidak ditemukan',
            ], 404);
        }

        $data->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data item penjualan berhasil dihapus',
        ], 200);
    }
}
