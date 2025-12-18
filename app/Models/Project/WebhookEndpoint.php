<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

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
}

