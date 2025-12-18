<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Payment extends Model
{
    use HasUlids;
    protected $fillable = [
        'invoice_id',
        'company_id',
        'payment_method_id',
        'amount_paid',
        'payment_date',
        'transaction_id',
        'status_id',
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    // Relations
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

