<?php

namespace App\Models\Core;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class UserCtaMessage extends Model{
    use HasUlids;
    
    protected $table = 'user_cta_messages';
    protected $primaryKey = 'id';
    public $incrementing = false;
    protected $keyType = 'string';
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

    public function user(){
        return $this->belongsTo(User::class, 'users_id', 'id')->select('id', 'name', 'email');
    }
}
