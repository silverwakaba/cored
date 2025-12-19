<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;
class Company extends Model
{
    use HasUlids, SoftDeletes;

    protected $fillable = [
        'uuid',
        'owner_id',
        'name',
        'industry',
        'company_code',
        'legal_entity_name',
        'registration_number',
        'tax_id',
        'website',
        'phone',
        'employee_count',
        'country_id',
        'state_province',
        'city',
        'postal_code',
        'address_line_1',
        'address_line_2',
        'is_active',
        'timezone',
        'default_currency',
        'default_language',
        'logo_url',
        'max_users',
        'max_storage_gb',
        'features_enabled',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'features_enabled' => 'array',
        'is_active' => 'boolean',
        'employee_count' => 'integer',
        'max_users' => 'integer',
        'max_storage_gb' => 'integer',
    ];

    // Relations
    public function owner()
    {
        return $this->hasOne(Owner::class, 'company_id');
    }

    public function ownerUser()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'company_id');
    }

    public function departments()
    {
        return $this->hasMany(Department::class, 'company_id');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'company_id');
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

    // Tax ID
    public function getTaxIdAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setTaxIdAttribute($value)
    {
        $this->attributes['tax_id'] = $value ? Crypt::encryptString($value) : null;
    }

    // Registration Number
    public function getRegistrationNumberAttribute($value)
    {
        return $value ? Crypt::decryptString($value) : null;
    }

    public function setRegistrationNumberAttribute($value)
    {
        $this->attributes['registration_number'] = $value ? Crypt::encryptString($value) : null;
    }
}

