<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'module_key',
        'title',
        'status',
        'started_at',
        'ended_at',
        'metadata',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'ended_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function attendanceRecords()
    {
        return $this->hasMany(StudentAttendance::class);
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function participationLogs()
    {
        return $this->hasMany(ModuleParticipationLog::class);
    }

    public function students()
    {
        return $this->belongsToMany(StudentProfile::class, 'attendance_records')
            ->withTimestamps();
    }
}