<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Timezone extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'timezone_name',
        'utc_offset',
        'is_dst_applicable',
        'is_active',
    ];

    protected $casts = [
        'is_dst_applicable' => 'boolean',
        'is_active' => 'boolean',
    ];
}

