<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Feedback360 extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $table = 'feedback_360';

    protected $fillable = [
        'company_id',
        'employee_id',
        'reviewer_id',
        'feedback_category_id',
        'rating',
        'comment',
        'is_anonymous',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'is_anonymous' => 'boolean',
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
        return $this->belongsTo(Employee::class, 'reviewer_id');
    }
}

