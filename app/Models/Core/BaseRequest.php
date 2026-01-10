<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class BaseRequest extends Model{
    use HasUlids;
    
    protected $table = 'base_requests';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'base_modules_id',
        'name',
        'detail',
        'is_active',
    ];

    // Belong to base module
    public function baseModule(){
        return $this->belongsTo(BaseModule::class, 'base_modules_id', 'id');
    }

    // Has many user requests
    public function userRequests(){
        return $this->hasMany(UserRequest::class, 'base_requests_id', 'id');
    }

    // Has many notifications
    public function notifications(){
        return $this->hasMany(Notification::class, 'base_requests_id', 'id');
    }
}
