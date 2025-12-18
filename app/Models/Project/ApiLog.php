<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class ApiLog extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'api_client_id',
        'endpoint',
        'method',
        'status_code',
        'request_size_bytes',
        'response_size_bytes',
        'response_time_ms',
        'error_message',
    ];

    protected $casts = [
        'status_code' => 'integer',
        'request_size_bytes' => 'integer',
        'response_size_bytes' => 'integer',
        'response_time_ms' => 'integer',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function apiClient()
    {
        return $this->belongsTo(ApiClient::class, 'api_client_id');
    }
}

