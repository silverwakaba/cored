<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class NotificationDelivery extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'notification_id',
        'delivery_channel_id',
        'recipient_address',
        'status_id',
        'sent_at',
        'failed_reason',
        'retry_count',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'retry_count' => 'integer',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function notification()
    {
        return $this->belongsTo(Notification::class, 'notification_id');
    }
}

