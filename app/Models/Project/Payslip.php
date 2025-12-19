<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Payslip extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'payroll_entry_id',
        'employee_id',
        'payslip_number',
        'payslip_pdf_url',
        'is_sent',
        'sent_at',
        'is_viewed',
        'viewed_at',
    ];

    protected $casts = [
        'is_sent' => 'boolean',
        'sent_at' => 'datetime',
        'is_viewed' => 'boolean',
        'viewed_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function payrollEntry()
    {
        return $this->belongsTo(PayrollEntry::class, 'payroll_entry_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
}

