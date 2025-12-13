<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SubscriptionPlan extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'code',
        'name',
        'description',
        'display_order',
        'base_price_monthly',
        'base_price_annual',
        'price_per_employee_monthly',
        'min_employees',
        'max_employees',
        'features',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'features' => 'array',
            'is_active' => 'boolean',
            'base_price_monthly' => 'decimal:2',
            'base_price_annual' => 'decimal:2',
            'price_per_employee_monthly' => 'decimal:2',
        ];
    }

    // Relationships
    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class, 'plan_id');
    }

    public function usageQuotas(): HasMany
    {
        return $this->hasMany(UsageQuota::class);
    }
}

