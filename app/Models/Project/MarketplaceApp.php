<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MarketplaceApp extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'name',
        'description',
        'developer_name',
        'is_free',
        'monthly_price',
        'rating',
        'review_count',
        'is_published',
        'icon_url',
        'documentation_url',
    ];

    protected function casts(): array
    {
        return [
            'is_free' => 'boolean',
            'monthly_price' => 'decimal:2',
            'rating' => 'decimal:2',
            'is_published' => 'boolean',
        ];
    }

    // Relationships
    public function installations(): HasMany
    {
        return $this->hasMany(AppInstallation::class, 'app_id');
    }
}
