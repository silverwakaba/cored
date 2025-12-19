<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class WebhookDelivery extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'webhook_endpoint_id',
        'event_type',
        'payload_json',
        'payload_hash',
        'status_id',
        'http_status_code',
        'response_time_ms',
        'retry_count',
        'next_retry_at',
        'last_error',
    ];

    protected $casts = [
        'http_status_code' => 'integer',
        'response_time_ms' => 'integer',
        'retry_count' => 'integer',
        'next_retry_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function webhookEndpoint()
    {
        return $this->belongsTo(WebhookEndpoint::class, 'webhook_endpoint_id');
    }
}

