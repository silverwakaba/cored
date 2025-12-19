<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class SyncConflict extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'device_id',
        'entity_type',
        'entity_id',
        'server_value',
        'device_value',
        'resolution_strategy_id',
        'resolved_value',
        'is_resolved',
    ];

    protected $casts = [
        'server_value' => 'array',
        'device_value' => 'array',
        'resolved_value' => 'array',
        'is_resolved' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

