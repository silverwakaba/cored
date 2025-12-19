<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

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

    // Encryption Accessors & Mutators for sensitive fields
    // Note: api_key_hashed field name suggests hashing, but for API keys that need to be retrieved,
    // encryption is more appropriate. Consider migrating existing hashed values to encrypted.

    // API Key (encrypted instead of hashed for retrieval purposes)
    public function getApiKeyHashedAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, might be old hashed value - return null
            return null;
        }
    }

    public function setApiKeyHashedAttribute($value)
    {
        $this->attributes['api_key_hashed'] = $value ? Crypt::encryptString($value) : null;
    }
}

