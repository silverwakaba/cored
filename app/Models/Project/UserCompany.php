<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserCompany extends Model
{
    use HasFactory, HasUlids;

    protected $fillable = [
        'user_id',
        'tenant_id',
        'primary_role',
        'employee_id',
        'is_active',
        'access_starts_at',
        'access_ends_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'access_starts_at' => 'datetime',
            'access_ends_at' => 'datetime',
        ];
    }

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class);
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}

