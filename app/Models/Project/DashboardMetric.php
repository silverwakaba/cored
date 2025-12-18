<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class DashboardMetric extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'metric_name',
        'metric_value',
        'metric_date',
        'time_period_id',
    ];

    protected $casts = [
        'metric_value' => 'decimal:2',
        'metric_date' => 'date',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

