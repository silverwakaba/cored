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
        'is_active',
    ];

    // Belong to base module
    public function belongsToBaseModule(){
        return $this->belongsTo(BaseModule::class, 'base_modules_id', 'id')->select('id', 'name');
    }

    // Has many user requests
    public function hasManyUserRequests(){
        return $this->hasMany(UserRequest::class, 'base_requests_id', 'id');
    }
}
