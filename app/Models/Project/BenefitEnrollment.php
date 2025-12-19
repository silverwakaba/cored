<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

class BenefitEnrollment extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'benefit_id',
        'enrollment_date',
        'start_date',
        'end_date',
        'status_id',
        'coverage_amount',
        'premium_amount',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'start_date' => 'date',
        'end_date' => 'date',
        // coverage_amount and premium_amount are encrypted, so don't cast them as decimal
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

    public function benefit()
    {
        return $this->belongsTo(Benefit::class, 'benefit_id');
    }

    // Encryption Accessors & Mutators for sensitive fields

    // Coverage Amount
    public function getCoverageAmountAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setCoverageAmountAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['coverage_amount'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['coverage_amount'] = null;
        }
    }

    // Premium Amount
    public function getPremiumAmountAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setPremiumAmountAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['premium_amount'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['premium_amount'] = null;
        }
    }
}

