<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Subscription extends Model
{
    use HasUlids;

    protected $fillable = [
        'company_id',
        'plan_id',
        'status_id',
        'subscription_start_date',
        'subscription_end_date',
        'trial_end_date',
        'auto_renew',
    ];

    protected $casts = [
        'subscription_start_date' => 'date',
        'subscription_end_date' => 'date',
        'trial_end_date' => 'date',
        'auto_renew' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id');
    }

    public function entitlements()
    {
        return $this->hasMany(Entitlement::class, 'subscription_id');
    }
}

