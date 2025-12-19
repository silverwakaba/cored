<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class DocumentExpiration extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'document_type_id',
        'document_name',
        'issue_date',
        'expiry_date',
        'days_until_expiry',
        'alert_sent',
        'alert_sent_at',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
        'days_until_expiry' => 'integer',
        'alert_sent' => 'boolean',
        'alert_sent_at' => 'datetime',
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

