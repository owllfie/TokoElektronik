<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Retur extends Model
{
    use SoftDeletes;

    protected $table = 'retur';
    protected $primaryKey = 'id_retur';
    public $timestamps = false;

    protected $fillable = [
        'id_barang',
        'keterangan',
        'tanggal_retur',
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
        'deleted_at',
        'deleted_by',
    ];

    protected $casts = [
        'tanggal_retur' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang', 'id_barang');
    }
}
