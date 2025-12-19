<?php

namespace App\Models\Project;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUlids;

class OfflineAttendanceQueue extends Model
{
    use HasUlids;
    protected $table = 'offline_attendance_queue';

    protected $fillable = [
        'company_id',
        'employee_id',
        'device_id',
        'clock_in_time',
        'clock_out_time',
        'geolocation',
        'sync_status_id',
        'synced_to_server_at',
    ];

    protected $casts = [
        'clock_in_time' => 'datetime',
        'clock_out_time' => 'datetime',
        'geolocation' => 'array',
        'synced_to_server_at' => 'datetime',
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
}

