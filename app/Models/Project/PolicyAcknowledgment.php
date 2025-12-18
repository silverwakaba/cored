<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class PolicyAcknowledgment extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'employee_id',
        'policy_id',
        'policy_name',
        'acknowledged_date',
        'acknowledged_by',
        'version_number',
        'reminder_sent_at',
    ];

    protected $casts = [
        'acknowledged_date' => 'datetime',
        'version_number' => 'integer',
        'reminder_sent_at' => 'datetime',
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

    public function acknowledgedBy()
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }
}

