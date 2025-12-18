<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class JobPosting extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'job_title',
        'job_code',
        'department_id',
        'job_description',
        'responsibilities',
        'requirements',
        'salary_range_min',
        'salary_range_max',
        'status_id',
        'posted_date',
        'closing_date',
        'locations',
        'job_type_id',
        'experience_required_years',
        'education_required_id',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'salary_range_min' => 'decimal:2',
        'salary_range_max' => 'decimal:2',
        'posted_date' => 'date',
        'closing_date' => 'date',
        'locations' => 'array',
        'experience_required_years' => 'integer',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'job_posting_id');
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

