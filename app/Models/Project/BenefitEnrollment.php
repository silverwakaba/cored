<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class BenefitEnrollment extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'benefit_id',
        'enrollment_date',
        'start_date',
        'end_date',
        'status_id',
        'coverage_amount',
        'premium_amount',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        'coverage_amount' => 'decimal:2',
        'premium_amount' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function benefit()
    {
        return $this->belongsTo(Benefit::class, 'benefit_id');
    }
}

