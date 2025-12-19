<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class NotificationTemplate extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'template_name',
        'template_code',
        'subject',
        'body_html',
        'placeholders',
        'notification_type_id',
        'is_active',
    ];

    protected $casts = [
        'placeholders' => 'array',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class, 'template_id');
    }
}

