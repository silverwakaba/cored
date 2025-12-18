<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Application extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'candidate_id',
        'job_posting_id',
        'status_id',
        'applied_date',
        'cover_letter_url',
        'rating',
        'screening_stage_id',
    ];

    protected $casts = [
        'applied_date' => 'date',
        'rating' => 'decimal:2',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function jobPosting()
    {
        return $this->belongsTo(JobPosting::class, 'job_posting_id');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'application_id');
    }

    public function offerLetter()
    {
        return $this->hasOne(OfferLetter::class, 'application_id');
    }
}

