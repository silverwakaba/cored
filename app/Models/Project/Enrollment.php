<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Enrollment extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'course_id',
        'enrollment_date',
        'completion_date',
        'status_id',
        'score',
        'passing_required_score',
        'certificate_issued',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'completion_date' => 'date',
        'score' => 'decimal:2',
        'passing_required_score' => 'decimal:2',
        'certificate_issued' => 'boolean',
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

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }
}

