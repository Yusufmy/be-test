<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'ID_NOTA';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ID_NOTA',
        'TGL',
        'KODE_PELANGGAN',
        'SUBTOTAL',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'KODE_PELANGGAN', 'ID_PELANGGAN');
    }

    public function itemPenjualan()
    {
        return $this->hasMany(ItemPenjualan::class, 'NOTA', 'ID_NOTA');
    }
}
