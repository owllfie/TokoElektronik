<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PageAccess extends Model
{
    protected $table = 'page_accesses';
    public $timestamps = false;

    protected $fillable = [
        'page_key',
        'role_id',
        'is_allowed',
        'updated_at',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'is_allowed' => 'boolean',
            'updated_at' => 'datetime',
        ];
    }
}
