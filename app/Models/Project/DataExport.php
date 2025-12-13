<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DataExport extends Model
{
    use HasFactory, HasUlids, SoftDeletes;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'export_type',
        'export_format',
        'filters',
        'file_path',
        'file_size',
        'status',
        'download_token',
        'download_expires_at',
        'downloaded_at',
        'requested_by',
    ];

    protected function casts(): array
    {
        return [
            'filters' => 'array',
            'download_expires_at' => 'datetime',
            'downloaded_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'requested_by');
    }
}

