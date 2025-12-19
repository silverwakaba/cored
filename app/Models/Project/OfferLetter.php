<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

class OfferLetter extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'application_id',
        'candidate_id',
        'offer_title',
        'offered_salary',
        'offered_salary_currency',
        'start_date',
        'offer_validity_date',
        'status_id',
        'offer_document_url',
        'accepted_at',
        'rejected_at',
        'rejection_reason',
        'created_by',
    ];

    protected $casts = [
        // offered_salary is encrypted, so don't cast it as decimal
        'start_date' => 'date',
        'offer_validity_date' => 'date',
        'accepted_at' => 'datetime',
        'rejected_at' => 'datetime',
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

    public function candidate()
    {
        return $this->belongsTo(Candidate::class, 'candidate_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Encryption Accessors & Mutators for sensitive fields

    // Offered Salary
    public function getOfferedSalaryAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setOfferedSalaryAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['offered_salary'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['offered_salary'] = null;
        }
    }
}

