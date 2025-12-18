<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Country extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'country_code',
        'country_name',
        'country_code_alpha3',
        'region',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

