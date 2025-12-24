<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BaseBoolean extends Model{
    protected $table = 'base_boolean';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'text',
        'value',
    ];

    protected $casts = [
        'value' => 'boolean',
    ];
}
