<?php

namespace App\Models\Project;

use App\Models\Core\BaseRequest;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TtbpMaster extends Model{
    use SoftDeletes;

    protected $table = 'ttbp_master';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'supplier_id',
        'base_currency_id',
        'base_status_ttbp_id',
        'base_status_payment_id',
        'number',
        'date',
        'due_date',
        'credit_day',
        'total',
        'bpb_file_path',
        'bpj_file_path',
        'note',
        'created_by',
        'updated_by',
        'deleted_by',
        'verified_by',
        'canceled_by',
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'total' => 'decimal:4',
        'verified_at' => 'datetime',
        'canceled_at' => 'datetime',
    ];

    // Belong to supplier
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Belong to base currency
    public function baseCurrency(){
        return $this->belongsTo(BaseRequest::class, 'base_currency_id', 'id');
    }

    // Belong to base status ttbp
    public function baseStatusTtbp(){
        return $this->belongsTo(BaseRequest::class, 'base_status_ttbp_id', 'id');
    }

    // Belong to base status payment
    public function baseStatusPayment(){
        return $this->belongsTo(BaseRequest::class, 'base_status_payment_id', 'id');
    }

    // Created by user
    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Updated by user
    public function updater(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // Deleted by user
    public function deleter(){
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    // Verified by user
    public function verifier(){
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

    // Canceled by user
    public function canceler(){
        return $this->belongsTo(User::class, 'canceled_by', 'id');
    }

    // Has many ttbp details
    public function ttbpDetails(){
        return $this->hasMany(TtbpDetail::class, 'ttbp_master_id');
    }

    // Belongs to many invoices through ttbp details
    public function invoices(){
        return $this->belongsToMany(Invoice::class, 'ttbp_detail', 'ttbp_master_id', 'invoice_id');
    }
}

