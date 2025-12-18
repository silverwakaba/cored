<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Course extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'course_name',
        'course_code',
        'description',
        'course_category_id',
        'duration_hours',
        'instructor_id',
        'start_date',
        'end_date',
        'max_participants',
        'course_type_id',
        'is_mandatory',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'duration_hours' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'max_participants' => 'integer',
        'is_mandatory' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function instructor()
    {
        return $this->belongsTo(Employee::class, 'instructor_id');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class, 'course_id');
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

