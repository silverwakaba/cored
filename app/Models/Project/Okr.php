<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Okr extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'objective_name',
        'objective_description',
        'key_result_1',
        'key_result_2',
        'key_result_3',
        'key_result_4',
        'period',
        'status_id',
        'created_by',
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

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

