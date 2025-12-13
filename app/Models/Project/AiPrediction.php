<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiPrediction extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'prediction_type',
        'target_entity_id',
        'target_entity_type',
        'prediction_value',
        'confidence_score',
        'factors',
        'model_version',
    ];

    protected function casts(): array
    {
        return [
            'prediction_value' => 'decimal:2',
            'confidence_score' => 'decimal:2',
            'factors' => 'array',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
