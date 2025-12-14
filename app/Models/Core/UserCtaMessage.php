<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Model;

class UserCtaMessage extends Model{
    protected $table = 'user_cta_messages';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'users_id',
        'subject',
        'message',
        'attachment',
    ];

    protected $casts = [
        'attachment' => 'array',
    ];

    public function belongsToUser(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select('id', 'name', 'email');
    }
}
