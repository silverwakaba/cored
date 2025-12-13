<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnalyticsSnapshot extends Model
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'metric_name',
        'metric_value',
        'dimension_1',
        'dimension_2',
        'dimension_3',
        'snapshot_date',
    ];

    protected function casts(): array
    {
        return [
            'metric_value' => 'decimal:2',
            'snapshot_date' => 'date',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}

