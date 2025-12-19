<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class Employee extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'employee_code',
        'first_name',
        'middle_name',
        'last_name',
        'email',
        'personal_email',
        'phone_primary',
        'phone_secondary',
        'date_of_birth',
        'gender',
        'nationality',
        'ssn_encrypted',
        'passport_encrypted',
        'marital_status',
        'employment_type_id',
        'status_id',
        'job_title',
        'department_id',
        'manager_id',
        'date_of_joining',
        'date_of_exit',
        'location',
        'work_phone',
        'office_email',
        'salary_encrypted',
        'salary_currency',
        'pay_frequency_id',
        'bank_account_encrypted',
        'residential_address_line_1',
        'residential_address_line_2',
        'residential_city',
        'residential_state',
        'residential_postal_code',
        'residential_country',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        // date_of_birth is encrypted, so don't cast it as date
        'date_of_joining' => 'date',
        'date_of_exit' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function manager()
    {
        return $this->belongsTo(Employee::class, 'manager_id');
    }

    public function subordinates()
    {
        return $this->hasMany(Employee::class, 'manager_id');
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Encryption Accessors & Mutators for sensitive fields

    // SSN
    public function getSsnEncryptedAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setSsnEncryptedAttribute($value)
    {
        $this->attributes['ssn_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }

    // Passport
    public function getPassportEncryptedAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setPassportEncryptedAttribute($value)
    {
        $this->attributes['passport_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }

    // Salary
    public function getSalaryEncryptedAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setSalaryEncryptedAttribute($value)
    {
        $this->attributes['salary_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }

    // Bank Account
    public function getBankAccountEncryptedAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setBankAccountEncryptedAttribute($value)
    {
        $this->attributes['bank_account_encrypted'] = $value ? Crypt::encryptString($value) : null;
    }

    // Personal Email
    public function getPersonalEmailAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setPersonalEmailAttribute($value)
    {
        $this->attributes['personal_email'] = $value ? Crypt::encryptString($value) : null;
    }

    // Email (work email - also sensitive)
    public function getEmailAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setEmailAttribute($value)
    {
        $this->attributes['email'] = $value ? Crypt::encryptString($value) : null;
    }

    // Phone Primary
    public function getPhonePrimaryAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setPhonePrimaryAttribute($value)
    {
        $this->attributes['phone_primary'] = $value ? Crypt::encryptString($value) : null;
    }

    // Phone Secondary
    public function getPhoneSecondaryAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setPhoneSecondaryAttribute($value)
    {
        $this->attributes['phone_secondary'] = $value ? Crypt::encryptString($value) : null;
    }

    // Date of Birth
    public function getDateOfBirthAttribute($value)
    {
        if (!$value) return null;
        try {
            $decrypted = Crypt::decryptString($value);
            // Parse the decrypted date string back to date
            return \Carbon\Carbon::parse($decrypted);
        } catch (\Exception $e) {
            // If decryption fails, return null
            return null;
        }
    }

    public function setDateOfBirthAttribute($value)
    {
        if ($value) {
            // Convert date to string before encrypting
            $dateString = $value instanceof \DateTimeInterface 
                ? $value->format('Y-m-d') 
                : (string) $value;
            $this->attributes['date_of_birth'] = Crypt::encryptString($dateString);
        } else {
            $this->attributes['date_of_birth'] = null;
        }
    }

    // Residential Address Line 1
    public function getResidentialAddressLine1Attribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setResidentialAddressLine1Attribute($value)
    {
        $this->attributes['residential_address_line_1'] = $value ? Crypt::encryptString($value) : null;
    }

    // Residential Address Line 2
    public function getResidentialAddressLine2Attribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setResidentialAddressLine2Attribute($value)
    {
        $this->attributes['residential_address_line_2'] = $value ? Crypt::encryptString($value) : null;
    }

    // Residential City
    public function getResidentialCityAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setResidentialCityAttribute($value)
    {
        $this->attributes['residential_city'] = $value ? Crypt::encryptString($value) : null;
    }

    // Residential State
    public function getResidentialStateAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setResidentialStateAttribute($value)
    {
        $this->attributes['residential_state'] = $value ? Crypt::encryptString($value) : null;
    }

    // Residential Postal Code
    public function getResidentialPostalCodeAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setResidentialPostalCodeAttribute($value)
    {
        $this->attributes['residential_postal_code'] = $value ? Crypt::encryptString($value) : null;
    }

    // Residential Country
    public function getResidentialCountryAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setResidentialCountryAttribute($value)
    {
        $this->attributes['residential_country'] = $value ? Crypt::encryptString($value) : null;
    }
}

