<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FeatureAccess extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'feature_access';

    protected $fillable = [
        'tenant_id',
        'feature_code',
        'feature_name',
        'is_enabled',
        'enabled_since',
        'enabled_until',
        'usage_count',
        'last_used_at',
        'granted_by',
    ];

    protected function casts(): array
    {
        return [
            'is_enabled' => 'boolean',
            'enabled_since' => 'date',
            'enabled_until' => 'date',
            'last_used_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function grantedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'granted_by');
    }
}
