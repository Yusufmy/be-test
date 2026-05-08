<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemPenjualan extends Model
{
    protected $table = 'item_penjualan';
    public $timestamps = false;


    protected $fillable = [
        'NOTA',
        'KODE_BARANG',
        'QTY',
    ];

    public function penjualan()
    {
        return $this->belongsTo(Penjualan::class, 'NOTA', 'ID_NOTA');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'KODE_BARANG', 'KODE');
    }
}
