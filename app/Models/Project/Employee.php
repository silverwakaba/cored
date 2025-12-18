<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'employee_code',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'personal_email',
        'phone_primary',
        'phone_secondary',
        'date_of_birth',
        'gender',
        'nationality',
        'ssn_encrypted',
        'passport_encrypted',
        'marital_status',
        'employment_type_id',
        'status_id',
        'job_title',
        'department_id',
        'manager_id',
        'date_of_joining',
        'date_of_exit',
        'location',
        'work_phone',
        'office_email',
        'salary_encrypted',
        'salary_currency',
        'pay_frequency_id',
        'bank_account_encrypted',
        'residential_address_line_1',
        'residential_address_line_2',
        'residential_city',
        'residential_state',
        'residential_postal_code',
        'residential_country',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'date_of_joining' => 'date',
        'date_of_exit' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
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

