<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BaseBoolean extends Model{
    use HasUlids;
    
    protected $table = 'base_boolean';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'text',
        'value',
    ];

    protected $casts = [
        'value' => 'boolean',
    ];
}
