<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BaseRequest extends Model{
    protected $table = 'base_requests';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'base_modules_id',
        'name',
        'value',
        'description',
        'display_order',
        'is_active',
    ];

    // Belong to base module
    public function baseModule(){
        return $this->belongsTo(BaseModule::class, 'base_modules_id', 'id')->select('id', 'name');
    }

    // Has many user requests
    public function userRequests(){
        return $this->hasMany(UserRequest::class, 'base_requests_id', 'id');
    }
}
