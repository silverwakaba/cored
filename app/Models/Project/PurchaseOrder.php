<?php

namespace App\Models\Project;

use App\Models\Core\BaseRequest;
use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PurchaseOrder extends Model{
    use SoftDeletes;

    protected $table = 'purchase_order';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'supplier_id',
        'base_status_id',
        'base_currency_id',
        'number',
        'date',
        'value',
        'vat',
        'created_by',
        'updated_by',
        'deleted_by',
    ];

    protected $casts = [
        'date' => 'date',
        'value' => 'decimal:4',
        'vat' => 'decimal:4',
    ];

    // Belong to supplier
    public function supplier(){
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Belong to base status
    public function baseStatus(){
        return $this->belongsTo(BaseRequest::class, 'base_status_id', 'id');
    }

    // Belong to base currency
    public function baseCurrency(){
        return $this->belongsTo(BaseRequest::class, 'base_currency_id', 'id');
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

    // Has many invoices
    public function invoices(){
        return $this->hasMany(Invoice::class, 'purchase_order_id');
    }
}

