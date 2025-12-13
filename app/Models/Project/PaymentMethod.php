<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PaymentMethod extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'type',
        'card_last_four',
        'card_brand',
        'card_expiry',
        'bank_code',
        'account_number',
        'account_holder',
        'wallet_provider',
        'wallet_account',
        'is_default',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'card_expiry' => 'date',
            'is_default' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }
}

