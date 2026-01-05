<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;

class TtbpDetail extends Model{
    protected $table = 'ttbp_detail';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'ttbp_master_id',
    ];

    // Belong to invoice
    public function invoice(){
        return $this->belongsTo(Invoice::class, 'invoice_id');
    }

    // Belong to ttbp master
    public function ttbpMaster(){
        return $this->belongsTo(TtbpMaster::class, 'ttbp_master_id');
    }
}

