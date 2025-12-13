<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomField extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'entity_type',
        'field_name',
        'field_label',
        'field_type',
        'field_options',
        'is_required',
        'is_visible',
        'display_order',
    ];

    protected function casts(): array
    {
        return [
            'field_options' => 'array',
            'is_required' => 'boolean',
            'is_visible' => 'boolean',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function values(): HasMany
    {
        return $this->hasMany(CustomFieldValue::class);
    }
}
