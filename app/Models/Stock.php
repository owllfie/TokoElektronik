<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $table = 'stock';
    protected $primaryKey = 'id_barang';
    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'jumlah',
        'harga_satuan',
        'total_harga',
        'tipe',
    ];
}
