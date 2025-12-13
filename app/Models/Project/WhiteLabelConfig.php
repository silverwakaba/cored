<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WhiteLabelConfig extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'tenant_id',
        'primary_color',
        'secondary_color',
        'accent_color',
        'logo_url',
        'favicon_url',
        'banner_url',
        'app_name',
        'company_name',
        'support_email',
        'support_phone',
        'custom_domain',
        'hide_powered_by',
        'custom_footer_text',
    ];

    protected function casts(): array
    {
        return [
            'hide_powered_by' => 'boolean',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }
}
