<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeWellnessTracking extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'wellness_program_id',
        'metric_name',
        'metric_value',
        'wellness_score',
        'risk_level',
        'recorded_at',
    ];

    protected function casts(): array
    {
        return [
            'metric_value' => 'decimal:2',
            'wellness_score' => 'decimal:2',
            'recorded_at' => 'datetime',
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

    public function wellnessProgram(): BelongsTo
    {
        return $this->belongsTo(WellnessProgram::class);
    }
}
