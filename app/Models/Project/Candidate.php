<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Candidate extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'current_title',
        'current_company',
        'resume_url',
        'portfolio_url',
        'years_of_experience',
        'source_id',
        'status_id',
        'rating',
        'notes',
    ];

    protected $casts = [
        'years_of_experience' => 'integer',
        'rating' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'candidate_id');
    }
}

