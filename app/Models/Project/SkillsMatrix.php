<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class SkillsMatrix extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $table = 'skills_matrix';

    protected $fillable = [
        'company_id',
        'skill_name',
        'skill_category_id',
        'proficiency_levels',
        'is_active',
    ];

    protected $casts = [
        'proficiency_levels' => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employeeSkills()
    {
        return $this->hasMany(EmployeeSkill::class, 'skill_id');
    }
}

