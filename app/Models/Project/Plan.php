<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasUlids;
    protected $fillable = [
        'name',
        'slug',
        'description',
        'base_price',
        'billing_period_id',
        'features',
        'max_employees',
        'max_storage_gb',
        'api_rate_limit',
        'is_active',
        'trial_days',
    ];

    protected $casts = [
        'features' => 'array',
        'base_price' => 'decimal:2',
        'max_employees' => 'integer',
        'max_storage_gb' => 'integer',
        'api_rate_limit' => 'integer',
        'is_active' => 'boolean',
        'trial_days' => 'integer',
    ];

    // Relations
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }
}

