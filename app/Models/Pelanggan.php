<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'ID_PELANGGAN';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'ID_PELANGGAN',
        'NAMA',
        'DOMISILI',
        'JENIS_KELAMIN',
    ];
}
