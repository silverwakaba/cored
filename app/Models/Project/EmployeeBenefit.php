<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeBenefit extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'employee_id',
        'benefit_plan_id',
        'enrollment_date',
        'enrollment_status',
        'dependent_count',
    ];

    protected function casts(): array
    {
        return [
            'enrollment_date' => 'date',
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

    public function benefitPlan(): BelongsTo
    {
        return $this->belongsTo(BenefitsPlan::class, 'benefit_plan_id');
    }
}

