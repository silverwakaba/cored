<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PerformanceGoal extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'title',
        'description',
        'target_value',
        'unit',
        'goal_year',
        'start_date',
        'end_date',
        'status',
        'current_progress',
        'set_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'target_value' => 'decimal:2',
            'current_progress' => 'decimal:2',
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

    public function setBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'set_by');
    }
}

