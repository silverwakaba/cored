<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Certification extends Model
{
    use HasUlids;
    public $timestamps = false;
    const CREATED_AT = 'created_at';

    protected $fillable = [
        'company_id',
        'certification_name',
        'issuing_organization',
        'certification_code',
        'validity_period_months',
        'is_active',
    ];

    protected $casts = [
        'validity_period_months' => 'integer',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employeeCertifications()
    {
        return $this->hasMany(EmployeeCertification::class, 'certification_id');
    }
}

