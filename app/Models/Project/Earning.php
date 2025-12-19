<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

class Earning extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'payroll_entry_id',
        'earning_type_id',
        'amount',
        'description',
    ];

    protected $casts = [
        // amount is encrypted, so don't cast it as decimal
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function payrollEntry()
    {
        return $this->belongsTo(PayrollEntry::class, 'payroll_entry_id');
    }

    // Encryption Accessors & Mutators for sensitive fields

    // Amount
    public function getAmountAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            return (float) $decrypted;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function setAmountAttribute($value)
    {
        if ($value !== null) {
            $this->attributes['amount'] = Crypt::encryptString((string) $value);
        } else {
            $this->attributes['amount'] = null;
        }
    }
}

