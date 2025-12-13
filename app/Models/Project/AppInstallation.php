<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AppInstallation extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'app_id',
        'status',
        'app_configuration',
        'api_key',
        'webhook_url',
        'installed_by',
        'installed_at',
    ];

    protected function casts(): array
    {
        return [
            'app_configuration' => 'array',
            'installed_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function app(): BelongsTo
    {
        return $this->belongsTo(MarketplaceApp::class, 'app_id');
    }

    public function installedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'installed_by');
    }
}
