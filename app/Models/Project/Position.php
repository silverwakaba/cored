<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Position extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'code',
        'title',
        'description',
        'level',
        'department_id',
        'parent_position_id',
        'salary_grade_id',
        'base_salary_min',
        'base_salary_max',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'base_salary_min' => 'decimal:2',
            'base_salary_max' => 'decimal:2',
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

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'parent_position_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Position::class, 'parent_position_id');
    }

    public function salaryGrade(): BelongsTo
    {
        return $this->belongsTo(SalaryGrade::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }
}

