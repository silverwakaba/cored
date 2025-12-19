<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class MobileSyncState extends Model
{
    use HasUlids;
    protected $table = 'mobile_sync_state';

    protected $fillable = [
        'company_id',
        'user_id',
        'device_id',
        'last_sync_timestamp',
        'last_sync_hash',
        'sync_status_id',
        'pending_changes_count',
    ];

    protected $casts = [
        'last_sync_timestamp' => 'datetime',
        'pending_changes_count' => 'integer',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

