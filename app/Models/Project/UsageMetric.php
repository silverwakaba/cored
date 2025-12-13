<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageMetric extends Model
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'metric_type',
        'metric_value',
        'period_date',
        'allocated_limit',
        'is_over_limit',
    ];

    protected function casts(): array
    {
        return [
            'period_date' => 'date',
            'metric_value' => 'decimal:2',
            'allocated_limit' => 'decimal:2',
            'is_over_limit' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

