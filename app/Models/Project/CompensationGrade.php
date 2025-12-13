<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompensationGrade extends Model
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'company_id',
        'code',
        'level',
        'minimum',
        'midpoint',
        'maximum',
        'market_benchmarked_rate',
        'is_active',
        'effective_date',
    ];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'minimum' => 'decimal:2',
            'midpoint' => 'decimal:2',
            'maximum' => 'decimal:2',
            'market_benchmarked_rate' => 'decimal:2',
            'is_active' => 'boolean',
            'created_at' => 'datetime',
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
}

