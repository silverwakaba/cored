<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class ItemMaster extends Model{
    use HasUlids;
    
    protected $table = 'item_masters';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
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
