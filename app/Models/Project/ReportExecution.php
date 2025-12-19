<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class ReportExecution extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'custom_report_id',
        'executed_by',
        'execution_date',
        'row_count',
        'file_url',
        'file_type_id',
    ];

    protected $casts = [
        'execution_date' => 'datetime',
        'row_count' => 'integer',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function customReport()
    {
        return $this->belongsTo(CustomReport::class, 'custom_report_id');
    }

    public function executedBy()
    {
        return $this->belongsTo(User::class, 'executed_by');
    }
}

