<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BenefitsPlan extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'code',
        'name',
        'description',
        'benefit_type',
        'coverage_amount',
        'monthly_premium',
        'company_contribution_percent',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'coverage_amount' => 'decimal:2',
            'monthly_premium' => 'decimal:2',
            'company_contribution_percent' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function employeeBenefits(): HasMany
    {
        return $this->hasMany(EmployeeBenefit::class, 'benefit_plan_id');
    }
}

