<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class EmployeeSkill extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'employee_id',
        'skill_id',
        'proficiency_level_id',
        'years_of_experience',
        'last_updated_at',
    ];

    protected $casts = [
        'years_of_experience' => 'decimal:2',
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

    public function skill()
    {
        return $this->belongsTo(SkillsMatrix::class, 'skill_id');
    }
}

