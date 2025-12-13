<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class BaseRequest extends Model{
    protected $table = 'base_requests';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}



