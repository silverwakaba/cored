<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceReview extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'review_period',
        'review_type',
        'overall_rating',
        'performance_rating',
        'behavioral_rating',
        'strengths',
        'development_areas',
        'comments',
        'reviewed_by',
        'reviewed_at',
        'review_period_start',
        'review_period_end',
    ];

    protected function casts(): array
    {
        return [
            'reviewed_at' => 'datetime',
            'review_period_start' => 'date',
            'review_period_end' => 'date',
            'overall_rating' => 'decimal:1',
            'performance_rating' => 'decimal:1',
            'behavioral_rating' => 'decimal:1',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'reviewed_by');
    }
}

