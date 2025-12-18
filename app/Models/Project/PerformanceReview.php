<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class PerformanceReview extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'review_period',
        'review_type_id',
        'overall_rating',
        'status_id',
        'review_comments',
        'reviewer_id',
        'review_date',
        'self_rating',
    ];

    protected $casts = [
        'overall_rating' => 'decimal:2',
        'review_date' => 'date',
        'self_rating' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function employeeReviews()
    {
        return $this->hasMany(EmployeeReview::class, 'performance_review_id');
    }
}

