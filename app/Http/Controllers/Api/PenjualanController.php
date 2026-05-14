<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Barang;
use App\Models\ItemPenjualan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PenjualanController extends Controller
{
    /**
     * GET /api/penjualan
     */
    public function index()
    {
        $data = Penjualan::with('pelanggan')
            ->orderByRaw("
            CAST(SUBSTRING_INDEX(ID_NOTA, '_', -1) AS UNSIGNED)
        ")
            ->get();

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
        $validator = Validator::make($request->all(), [
            "TGL" => "required|date",
            "KODE_PELANGGAN" => "required|exists:pelanggan,ID_PELANGGAN",
            "items" => "required|array|min:1",
            "items.*.KODE_BARANG" => "required|exists:barang,KODE",
            "items.*.QTY" => "required|integer|min:1",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }


        DB::beginTransaction();

        try {
            $lastNumber = Penjualan::selectRaw("
            MAX(CAST(SUBSTRING_INDEX(ID_NOTA, '_', -1) AS UNSIGNED)) as max_id
        ")->value('max_id');

            $newId = 'NOTA_' . (($lastNumber ?? 0) + 1);

            $subtotal = 0;

            foreach ($request->items as $item) {
                $barang = Barang::find($item['KODE_BARANG']);
                $subtotal += $barang->HARGA * $item['QTY'];
            }

            $penjualan = Penjualan::create([
                'ID_NOTA' => $newId,
                'TGL' => $request->TGL,
                'KODE_PELANGGAN' => $request->KODE_PELANGGAN,
                'SUBTOTAL' => $subtotal,
            ]);

            foreach ($request->items as $item) {
                ItemPenjualan::create([
                    'NOTA' => $newId,
                    'KODE_BARANG' => $item['KODE_BARANG'],
                    'QTY' => $item['QTY'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data penjualan berhasil ditambahkan',
                'data' => $penjualan->load('itemPenjualan')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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
            "TGL" => "required|date",
            "KODE_PELANGGAN" => "sometimes|exists:pelanggan,ID_PELANGGAN",
            "items" => "sometimes|array|min:1",
            "items.*.KODE_BARANG" => "sometimes|exists:barang,KODE",
            "items.*.QTY" => "sometimes|integer|min:1",
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();

        try {
            $subtotal = 0;

            foreach ($request->items as $item) {
                $barang = Barang::find($item['KODE_BARANG']);
                $subtotal += $barang->HARGA * $item['QTY'];
            }

            $data->update([
                'TGL' => $request->TGL,
                'KODE_PELANGGAN' => $request->KODE_PELANGGAN,
                'SUBTOTAL' => $subtotal,
            ]);

            ItemPenjualan::where('NOTA', $id)->delete();

            foreach ($request->items as $item) {
                ItemPenjualan::create([
                    'NOTA' => $id,
                    'KODE_BARANG' => $item['KODE_BARANG'],
                    'QTY' => $item['QTY'],
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data penjualan berhasil diupdate',
                'data' => $data->load('itemPenjualan')
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
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

        DB::beginTransaction();

        try {
            $data->itemPenjualan()->delete();
            $data->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Data penjualan berhasil dihapus',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
