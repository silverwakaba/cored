<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

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
        // Salary fields are encrypted, so don't cast them as decimal
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

    // Encryption Accessors & Mutators for sensitive fields

    // Base Salary
    public function getBaseSalaryAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setBaseSalaryAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['base_salary'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['base_salary'] = null;
        }
    }

    // Gross Salary
    public function getGrossSalaryAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setGrossSalaryAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['gross_salary'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['gross_salary'] = null;
        }
    }

    // Net Salary
    public function getNetSalaryAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setNetSalaryAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['net_salary'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['net_salary'] = null;
        }
    }
}

