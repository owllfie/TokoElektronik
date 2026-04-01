<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RecordHistory extends Model
{
    protected $table = 'record_histories';
    public $timestamps = false;

    protected $fillable = [
        'entity_type',
        'record_id',
        'action',
        'before_state',
        'after_state',
        'changed_by',
        'created_at',
    ];

    protected $casts = [
        'before_state' => 'array',
        'after_state' => 'array',
        'created_at' => 'datetime',
    ];
}
