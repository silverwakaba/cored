<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class OnboardingChecklist extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'checklist_item',
        'status_id',
        'assigned_to',
        'due_date',
        'completed_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completed_date' => 'date',
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

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}

