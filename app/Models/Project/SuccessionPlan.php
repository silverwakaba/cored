<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SuccessionPlan extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'position_id',
        'primary_successor_id',
        'primary_readiness_level',
        'backup_successor_id',
        'backup_readiness_level',
        'plan_date',
        'target_succession_date',
    ];

    protected function casts(): array
    {
        return [
            'plan_date' => 'date',
            'target_succession_date' => 'date',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function primarySuccessor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'primary_successor_id');
    }

    public function backupSuccessor(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'backup_successor_id');
    }
}

