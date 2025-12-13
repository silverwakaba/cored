<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WorkflowStep extends Model
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'workflow_id',
        'step_order',
        'step_name',
        'approver_rule',
        'conditions',
        'approval_timeout_days',
        'auto_approve_on_timeout',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'conditions' => 'array',
            'auto_approve_on_timeout' => 'boolean',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function workflow(): BelongsTo
    {
        return $this->belongsTo(Workflow::class);
    }

    public function approvals(): HasMany
    {
        return $this->hasMany(Approval::class);
    }
}

