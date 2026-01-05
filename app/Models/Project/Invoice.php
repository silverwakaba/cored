<?php

namespace App\Models\Project;

use App\Models\Core\BaseRequest;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Invoice extends Model{
    use SoftDeletes;

    protected $table = 'invoice';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'supplier_id',
        'purchase_order_id',
        'base_status_id',
        'base_work_type_id',
        'base_tax_type_id',
        'po_file_path',
        'invoice_number',
        'invoice_date',
        'invoice_value',
        'invoice_file_path',
        'invoice_note',
        'sjba_number',
        'sjba_file_path',
        'sjba_note',
        'tax_invoice_number',
        'tax_invoice_file_path',
        'tax_invoice_note',
        'other_name',
        'other_number',
        'other_file_path',
        'other_note',
        'created_by',
        'updated_by',
        'deleted_by',
        'verified_by',
        'unverified_by',
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'invoice_value' => 'decimal:4',
        'verified_at' => 'datetime',
        'unverified_at' => 'datetime',
    ];

    // Belong to supplier
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Belong to purchase order
    public function purchaseOrder(){
        return $this->belongsTo(PurchaseOrder::class, 'purchase_order_id');
    }

    // Belong to base status
    public function baseStatus(){
        return $this->belongsTo(BaseRequest::class, 'base_status_id', 'id');
    }

    // Belong to base work type
    public function baseWorkType(){
        return $this->belongsTo(BaseRequest::class, 'base_work_type_id', 'id');
    }

    // Belong to base tax type
    public function baseTaxType(){
        return $this->belongsTo(BaseRequest::class, 'base_tax_type_id', 'id');
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

    // Unverified by user
    public function unverifier(){
        return $this->belongsTo(User::class, 'unverified_by', 'id');
    }

    // Has many ttbp details
    public function ttbpDetails(){
        return $this->hasMany(TtbpDetail::class, 'invoice_id');
    }

    // Belongs to many ttbp masters through ttbp details
    public function ttbpMasters(){
        return $this->belongsToMany(TtbpMaster::class, 'ttbp_detail', 'invoice_id', 'ttbp_master_id');
    }
}

