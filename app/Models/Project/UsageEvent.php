<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageEvent extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'user_id',
        'event_type',
        'event_category',
        'resource_id',
        'resource_type',
        'quantity_used',
        'unit_of_measure',
        'cost_per_unit',
        'ip_address',
        'user_agent',
        'processed_at',
    ];

    protected function casts(): array
    {
        return [
            'quantity_used' => 'decimal:4',
            'cost_per_unit' => 'decimal:6',
            'processed_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class);
    }
}
