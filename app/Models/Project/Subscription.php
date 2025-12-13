<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Subscription extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'plan_id',
        'plan_name',
        'monthly_price',
        'annual_price',
        'billing_cycle',
        'employee_count_tier',
        'max_employees',
        'status',
        'started_at',
        'renews_at',
        'cancelled_at',
        'enabled_features',
        'payment_method_id',
        'auto_renew',
    ];

    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'renews_at' => 'datetime',
            'cancelled_at' => 'datetime',
            'enabled_features' => 'array',
            'auto_renew' => 'boolean',
            'monthly_price' => 'decimal:2',
            'annual_price' => 'decimal:2',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function plan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'plan_id');
    }

    public function paymentMethod(): BelongsTo
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }
}

