<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class UsageMetric extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'metric_type',
        'metric_value',
        'period_date',
        'period_type',
        'month_year',
        'quantity_used',
        'limit_allowed',
    ];

    protected $casts = [
        'metric_value' => 'decimal:2',
        'period_date' => 'date',
        'quantity_used' => 'decimal:2',
        'limit_allowed' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

