<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageAlert extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'alert_type',
        'event_type',
        'current_usage',
        'quota_limit',
        'usage_percent',
        'is_acknowledged',
        'acknowledged_by',
        'acknowledged_at',
        'notification_sent_to',
    ];

    protected function casts(): array
    {
        return [
            'current_usage' => 'decimal:4',
            'quota_limit' => 'decimal:4',
            'usage_percent' => 'decimal:2',
            'is_acknowledged' => 'boolean',
            'acknowledged_at' => 'datetime',
            'notification_sent_to' => 'array',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'acknowledged_by');
    }
}
