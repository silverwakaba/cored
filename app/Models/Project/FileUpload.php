<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;
use Illuminate\Database\Eloquent\SoftDeletes;

class FileUpload extends Model
{
    use HasUlids;

    protected $fillable = [
        'company_id',
        'user_id',
        'file_name',
        'file_path',
        'file_mime_type',
        'file_size_bytes',
        'storage_service_id',
        'entity_type',
        'entity_id',
        'virus_scanned',
        'is_secure',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
        'virus_scanned' => 'boolean',
        'is_secure' => 'boolean',
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

