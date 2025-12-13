<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UsageQuota extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'subscription_plan_id',
        'event_type',
        'quota_limit',
        'unit_of_measure',
        'period_type',
        'overage_rate',
        'is_hard_limit',
        'warning_threshold_percent',
    ];

    protected function casts(): array
    {
        return [
            'quota_limit' => 'decimal:4',
            'overage_rate' => 'decimal:6',
            'warning_threshold_percent' => 'decimal:2',
            'is_hard_limit' => 'boolean',
        ];
    }

    // Relationships
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }
}
