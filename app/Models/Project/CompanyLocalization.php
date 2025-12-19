<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class CompanyLocalization extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'language_id',
        'currency_id',
        'timezone_id',
        'date_format',
        'time_format',
        'decimal_separator',
        'thousands_separator',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id');
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_id');
    }

    public function timezone()
    {
        return $this->belongsTo(Timezone::class, 'timezone_id');
    }
}

