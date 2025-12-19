<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class Benefit extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'benefit_name',
        'benefit_code',
        'benefit_type_id',
        'description',
        'provider_name',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function enrollments()
    {
        return $this->hasMany(BenefitEnrollment::class, 'benefit_id');
    }
}

