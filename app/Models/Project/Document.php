<?php

namespace App\Models\Project;

use App\Models\Core\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\\Eloquent\\Concerns\\HasUlids;

class Document extends Model
{
    use HasUlids;
    protected $fillable = [
        'company_id',
        'employee_id',
        'document_type_id',
        'document_name',
        'file_url',
        'file_size_bytes',
        'upload_date',
        'expiry_date',
        'is_active',
        'created_by',
    ];

    protected $casts = [
        'file_size_bytes' => 'integer',
        'upload_date' => 'datetime',
        'expiry_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relations
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

