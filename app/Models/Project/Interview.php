<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Interview extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'application_id',
        'interviewer_id',
        'interview_type_id',
        'scheduled_date',
        'duration_minutes',
        'location_or_link',
        'status_id',
        'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'datetime',
        'duration_minutes' => 'integer',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }

    public function feedbacks()
    {
        return $this->hasMany(InterviewFeedback::class, 'interview_id');
    }
}

