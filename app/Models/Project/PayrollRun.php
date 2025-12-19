<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class PayrollRun extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'payroll_cycle_id',
        'period_start_date',
        'period_end_date',
        'payment_date',
        'status_id',
        'total_gross',
        'total_deductions',
        'total_taxes',
        'total_net',
        'total_employees',
        'processed_count',
        'error_count',
        'locked_at',
        'locked_by',
        'finalized_at',
        'finalized_by',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'period_start_date' => 'date',
        'period_end_date' => 'date',
        'payment_date' => 'date',
        'total_gross' => 'decimal:2',
        'total_deductions' => 'decimal:2',
        'total_taxes' => 'decimal:2',
        'total_net' => 'decimal:2',
        'total_employees' => 'integer',
        'processed_count' => 'integer',
        'error_count' => 'integer',
        'locked_at' => 'datetime',
        'finalized_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function entries()
    {
        return $this->hasMany(PayrollEntry::class, 'payroll_run_id');
    }

    public function lockedBy()
    {
        return $this->belongsTo(User::class, 'locked_by');
    }

    public function finalizedBy()
    {
        return $this->belongsTo(User::class, 'finalized_by');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}

