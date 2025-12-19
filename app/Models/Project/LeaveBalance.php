<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class LeaveBalance extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'leave_type_id',
        'fiscal_year',
        'opening_balance',
        'earned_balance',
        'used_balance',
        'carryover_balance',
        'closing_balance',
        'last_updated_at',
    ];

    protected $casts = [
        'opening_balance' => 'decimal:2',
        'earned_balance' => 'decimal:2',
        'used_balance' => 'decimal:2',
        'carryover_balance' => 'decimal:2',
        'closing_balance' => 'decimal:2',
        'last_updated_at' => 'datetime',
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

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id');
    }
}

