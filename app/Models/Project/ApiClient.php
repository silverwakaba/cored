<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class ApiClient extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'client_name',
        'api_key_hashed',
        'api_key_prefix',
        'rate_limit_requests',
        'rate_limit_window_seconds',
        'is_active',
        'last_used_at',
        'created_by',
    ];

    protected $casts = [
        'rate_limit_requests' => 'integer',
        'rate_limit_window_seconds' => 'integer',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function logs()
    {
        return $this->hasMany(ApiLog::class, 'api_client_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

