<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class CustomReport extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'report_name',
        'report_type_id',
        'query_definition',
        'filters',
        'columns',
        'created_by',
    ];

    protected $casts = [
        'query_definition' => 'array',
        'filters' => 'array',
        'columns' => 'array',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function executions()
    {
        return $this->hasMany(ReportExecution::class, 'custom_report_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

