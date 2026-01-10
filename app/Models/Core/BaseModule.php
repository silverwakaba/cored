<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BaseModule extends Model{
    use HasUlids;
    
    protected $table = 'base_modules';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'is_active',
    ];

    // Has many base requests
    public function baseRequests(){
        return $this->hasMany(BaseRequest::class, 'base_modules_id', 'id');
    }
}
