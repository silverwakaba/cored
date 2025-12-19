<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class InterviewFeedback extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'interview_id',
        'interviewer_id',
        'rating',
        'technical_skills_rating',
        'communication_rating',
        'cultural_fit_rating',
        'feedback_comments',
        'recommendation_id',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'technical_skills_rating' => 'decimal:2',
        'communication_rating' => 'decimal:2',
        'cultural_fit_rating' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'interview_id');
    }

    public function interviewer()
    {
        return $this->belongsTo(User::class, 'interviewer_id');
    }
}

