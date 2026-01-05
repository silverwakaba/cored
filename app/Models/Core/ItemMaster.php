<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class ItemMaster extends Model{
    protected $table = 'item_masters';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'description',
    ];

    // Has many item details
    public function details(){
        return $this->hasMany(ItemDetail::class, 'item_masters_id');
    }
}
