<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class UserRequest extends Model{
    protected $table = 'user_requests';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'base_requests_id',
        'users_id',
        'token',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select('id', 'name', 'email');
    }

    public function baseRequest(){
        return $this->belongsTo(BaseRequest::class, 'base_requests_id', 'id')->select('id', 'name');
    }
}
