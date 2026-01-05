<?php

namespace App\Models\Project;

use App\Models\Core\BaseRequest;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model{
    protected $table = 'supplier';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'users_id',
        'base_qualification_id',
        'base_business_entity_id',
        'base_bank_id',
        'code',
        'name',
        'credit_day',
        'address_1',
        'address_2',
        'telp',
        'fax',
        'npwp',
        'npwp_address',
        'bank_account_name',
        'bank_account_number',
        'pkp',
        'nib',
        'notes',
        'statement_file_path',
        'is_active',
        'created_by',
        'updated_by',
    ];

    // Belong to user (vendor portal user)
    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }

    // Belong to base qualification
    public function baseQualification(){
        return $this->belongsTo(BaseRequest::class, 'base_qualification_id', 'id');
    }

    // Belong to base business entity
    public function baseBusinessEntity(){
        return $this->belongsTo(BaseRequest::class, 'base_business_entity_id', 'id');
    }

    // Belong to base bank
    public function baseBank(){
        return $this->belongsTo(BaseRequest::class, 'base_bank_id', 'id');
    }

    // Created by user
    public function creator(){
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    // Updated by user
    public function updater(){
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // Has many purchase orders
    public function purchaseOrders(){
        return $this->hasMany(PurchaseOrder::class, 'supplier_id');
    }

    // Has many invoices
    public function invoices(){
        return $this->hasMany(Invoice::class, 'supplier_id');
    }

    // Has many ttbp masters
    public function ttbpMasters(){
        return $this->hasMany(TtbpMaster::class, 'supplier_id');
    }
}

