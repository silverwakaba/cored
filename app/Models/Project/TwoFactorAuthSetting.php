<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class TwoFactorAuthSetting extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'user_id',
        'is_enabled',
        'method_type_id',
        'secret_key_encrypted',
        'backup_codes_hashed',
        'last_verified_at',
    ];

    protected $casts = [
        'is_enabled' => 'boolean',
        'last_verified_at' => 'datetime',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}

