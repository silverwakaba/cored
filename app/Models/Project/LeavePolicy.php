<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class LeavePolicy extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'name',
        'days_per_year',
        'carryover_percentage',
        'carryover_limit',
        'probation_period_days',
        'min_notice_days',
        'max_consecutive_days',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'days_per_year' => 'integer',
        'carryover_percentage' => 'decimal:2',
        'carryover_limit' => 'integer',
        'probation_period_days' => 'integer',
        'min_notice_days' => 'integer',
        'max_consecutive_days' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
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

