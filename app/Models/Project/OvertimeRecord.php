<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class OvertimeRecord extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'work_date',
        'overtime_hours',
        'overtime_type_id',
        'rate_multiplier',
        'amount',
        'approved_by',
        'is_approved',
    ];

    protected $casts = [
        'work_date' => 'date',
        'overtime_hours' => 'decimal:2',
        'rate_multiplier' => 'decimal:2',
        'amount' => 'decimal:2',
        'is_approved' => 'boolean',
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

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}

