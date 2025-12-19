<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

class WebhookEndpoint extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'endpoint_url',
        'webhook_events',
        'is_active',
        'secret_key_hashed',
        'created_by',
    ];

    protected $casts = [
        'webhook_events' => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function deliveries()
    {
        return $this->hasMany(WebhookDelivery::class, 'webhook_endpoint_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Encryption Accessors & Mutators for sensitive fields
    // Note: secret_key_hashed field name suggests hashing, but for secret keys that need to be retrieved,
    // encryption is more appropriate. Consider migrating existing hashed values to encrypted.

    // Secret Key (encrypted instead of hashed for retrieval purposes)
    public function getSecretKeyHashedAttribute($value)
    {
        if (!$value) return null;
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            // If decryption fails, might be old hashed value - return null
            return null;
        }
    }

    public function setSecretKeyHashedAttribute($value)
    {
        $this->attributes['secret_key_hashed'] = $value ? Crypt::encryptString($value) : null;
    }
}

