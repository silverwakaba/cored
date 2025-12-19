<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

class Reimbursement extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'payroll_entry_id',
        'employee_id',
        'reimbursement_type_id',
        'amount',
        'description',
        'receipt_url',
        'status_id',
        'approved_by',
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

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
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

