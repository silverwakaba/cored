<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Notification extends Model
{
    use HasFactory, HasUlids;

    public $timestamps = false;

    protected $fillable = [
        'tenant_id',
        'recipient_user_id',
        'title',
        'message',
        'notification_type',
        'related_entity_id',
        'related_entity_type',
        'is_read',
        'is_deleted',
        'send_via_email',
        'email_sent_at',
        'send_via_push',
        'push_sent_at',
        'send_via_sms',
        'sms_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'is_read' => 'boolean',
            'is_deleted' => 'boolean',
            'send_via_email' => 'boolean',
            'send_via_push' => 'boolean',
            'send_via_sms' => 'boolean',
            'email_sent_at' => 'datetime',
            'push_sent_at' => 'datetime',
            'sms_sent_at' => 'datetime',
            'created_at' => 'datetime',
        ];
    }

    // Relationships
    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function recipient(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Core\User::class, 'recipient_user_id');
    }
}

