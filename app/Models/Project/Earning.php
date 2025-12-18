<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

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
        'amount' => 'decimal:2',
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
}

