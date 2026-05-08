<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    /**
     * GET /api/pelanggan
     */
    public function index()
    {
        $data = Pelanggan::all();

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil diambil',
            'data' => $data
        ], 200);
    }

    /**
     * POST /api/pelanggan
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'NAMA' => 'required|string|max:255',
            'DOMISILI' => 'required|string|max:255',
            'JENIS_KELAMIN' => 'required|in:PRIA,WANITA',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $lastNumber = Pelanggan::selectRaw("
            MAX(CAST(SUBSTRING_INDEX(ID_PELANGGAN, '_', -1) AS UNSIGNED)) as max_id
        ")->value('max_id');

        $newId = 'PELANGGAN_' . (($lastNumber ?? 0) + 1);

        $pelanggan = Pelanggan::create([
            'ID_PELANGGAN' => $newId,
            'NAMA' => $request->NAMA,
            'DOMISILI' => $request->DOMISILI,
            'JENIS_KELAMIN' => $request->JENIS_KELAMIN,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil disimpan',
            'data' => $pelanggan
        ], 201);
    }

    /**
     * GET /api/pelanggan/{id}
     */
    public function show(string $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil diambil',
            'data' => $pelanggan
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'NAMA' => 'sometimes|string|max:255',
            'DOMISILI' => 'sometimes|string|max:255',
            'JENIS_KELAMIN' => 'sometimes|in:PRIA,WANITA',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        $pelanggan->update($request->only([
            'NAMA',
            'DOMISILI',
            'JENIS_KELAMIN'
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil diperbarui',
            'data' => $pelanggan
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $pelanggan = Pelanggan::find($id);

        if (!$pelanggan) {
            return response()->json([
                'success' => false,
                'message' => 'Data pelanggan tidak ditemukan'
            ], 404);
        }

        $pelanggan->delete();

        return response()->json([
            'success' => true,
            'message' => 'Data pelanggan berhasil dihapus'
        ], 200);
    }
}
