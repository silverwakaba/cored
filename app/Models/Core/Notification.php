<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model{
    protected $table = 'notifications';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'base_requests_id',
        'base_statuses_id',
        'users_id',
        'data',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts() : array{
        return [
            'data'      => 'json',
            'read_at'   => 'datetime',
        ];
    }

    // Belong to base request
    public function baseRequest(){
        return $this->belongsTo(BaseRequest::class, 'base_requests_id', 'id');
    }

    // Belong to base request
    public function baseStatus(){
        return $this->belongsTo(BaseRequest::class, 'base_statuses_id', 'id');
    }

    // Belong to user
    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id');
    }
}

