<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BaseModule extends Model{
    protected $table = 'base_modules';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];

    // Has many base requests
    public function hasManyBaseRequests(){
        return $this->hasMany(BaseRequest::class, 'base_modules_id', 'id');
    }
}
