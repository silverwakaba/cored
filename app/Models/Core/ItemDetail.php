<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model{
    use HasUlids;
    
    protected $table = 'item_details';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'item_masters_id',
        'name',
        'description',
    ];

    // Belong to item master
    public function master(){
        return $this->belongsTo(ItemMaster::class, 'item_masters_id');
    }
}
