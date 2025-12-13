<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TenantOnboarding extends Model
{
    use HasFactory, HasUlids;

    protected $table = 'tenant_onboarding';

    protected $fillable = [
        'tenant_id',
        'step_1_company_info_completed',
        'step_2_admin_user_created',
        'step_3_employee_data_imported',
        'step_4_access_configured',
        'step_5_initial_setup_complete',
        'overall_completion_percent',
        'onboarding_started_at',
        'onboarding_completed_at',
        'is_high_touch',
        'assigned_onboarding_specialist',
    ];

    protected function casts(): array
    {
        return [
            'step_1_company_info_completed' => 'boolean',
            'step_2_admin_user_created' => 'boolean',
            'step_3_employee_data_imported' => 'boolean',
            'step_4_access_configured' => 'boolean',
            'step_5_initial_setup_complete' => 'boolean',
            'is_high_touch' => 'boolean',
            'onboarding_started_at' => 'datetime',
            'onboarding_completed_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function onboardingSpecialist(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'assigned_onboarding_specialist');
    }
}

