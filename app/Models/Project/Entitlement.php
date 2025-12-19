<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Entitlement extends Model
{
    use HasUlids;
    protected $fillable = [
        'subscription_id',
        'feature_name',
        'limit_value',
        'current_usage',
        'reset_frequency_id',
        'last_reset_at',
    ];

    protected $casts = [
        'limit_value' => 'integer',
        'current_usage' => 'integer',
        'last_reset_at' => 'datetime',
    ];

    // Relations
    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }
}

