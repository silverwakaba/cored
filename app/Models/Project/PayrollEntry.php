<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class PayrollEntry extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'payroll_run_id',
        'employee_id',
        'base_salary',
        'gross_salary',
        'net_salary',
        'status_id',
    ];

    protected $casts = [
        'base_salary' => 'decimal:2',
        'gross_salary' => 'decimal:2',
        'net_salary' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function payrollRun()
    {
        return $this->belongsTo(PayrollRun::class, 'payroll_run_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function earnings()
    {
        return $this->hasMany(Earning::class, 'payroll_entry_id');
    }

    public function deductions()
    {
        return $this->hasMany(Deduction::class, 'payroll_entry_id');
    }

    public function taxes()
    {
        return $this->hasMany(Tax::class, 'payroll_entry_id');
    }

    public function payslip()
    {
        return $this->hasOne(Payslip::class, 'payroll_entry_id');
    }
}

