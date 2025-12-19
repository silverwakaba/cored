<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Support\Facades\Crypt;

class OvertimeRecord extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'work_date',
        'overtime_hours',
        'overtime_type_id',
        'rate_multiplier',
        'amount',
        'approved_by',
        'is_approved',
    ];

    protected $casts = [
        'work_date' => 'date',
        'overtime_hours' => 'decimal:2',
        'rate_multiplier' => 'decimal:2',
        // amount is encrypted, so don't cast it as decimal
        'is_approved' => 'boolean',
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

