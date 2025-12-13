<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SystemConfiguration extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'config_key',
        'config_value',
        'config_type',
        'description',
        'requires_restart',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'config_value' => 'array',
            'requires_restart' => 'boolean',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'updated_by');
    }
}

