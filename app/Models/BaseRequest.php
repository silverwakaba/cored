<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BaseRequest extends Model{
    protected $table = 'base_requests';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
    ];
}
