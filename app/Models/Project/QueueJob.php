<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class QueueJob extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'queue_name',
        'job_type',
        'payload_json',
        'status_id',
        'attempts',
        'max_attempts',
        'last_error',
        'reserved_at',
        'available_at',
    ];

    protected $casts = [
        'attempts' => 'integer',
        'max_attempts' => 'integer',
        'reserved_at' => 'datetime',
        'available_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

