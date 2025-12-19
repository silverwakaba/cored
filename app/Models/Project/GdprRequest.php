<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class GdprRequest extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'request_type_id',
        'request_date',
        'status_id',
        'reason',
        'response_data_url',
        'completed_date',
        'deletion_scheduled_for',
    ];

    protected $casts = [
        'request_date' => 'date',
        'completed_date' => 'date',
        'deletion_scheduled_for' => 'date',
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
}

