<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class AttendanceAdjustment extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'attendance_id',
        'adjustment_type_id',
        'hours_adjusted',
        'reason',
        'approved_by',
        'is_approved',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'hours_adjusted' => 'decimal:2',
        'is_approved' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
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

