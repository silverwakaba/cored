<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model{
    protected $table = 'users_requests';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'base_requests_id',
        'users_id',
        'token',
    ];

    public function belongsToUser(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select('id', 'name', 'email');
    }

    public function belongsToBaseRequest(){
        return $this->belongsTo(BaseRequest::class, 'base_requests_id', 'id')->select('id', 'name');
    }
}
