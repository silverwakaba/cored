<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class EmployeeCertification extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'certification_id',
        'issue_date',
        'expiry_date',
        'certificate_url',
        'is_active',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
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

    public function certification()
    {
        return $this->belongsTo(Certification::class, 'certification_id');
    }
}

