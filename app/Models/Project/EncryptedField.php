<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class EncryptedField extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'entity_type',
        'entity_id',
        'field_name',
        'encrypted_value',
        'encryption_key_version',
        'access_log_enabled',
    ];

    protected $casts = [
        'encryption_key_version' => 'integer',
        'access_log_enabled' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }
}

