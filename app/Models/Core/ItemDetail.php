<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ItemDetail extends Model{
    protected $table = 'item_details';
    protected $primaryKey = 'id';
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
