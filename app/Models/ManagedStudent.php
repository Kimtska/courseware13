<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class ManagedStudent extends Authenticatable
{
    use HasFactory;

    protected $table = 'students';

    protected $fillable = [
        'instructor_user_id',
        'student_id_number',
        'email',
        'password',
        'status',
        'full_name',
        'section',
        'enrollment_status',
        'module_access_status',
        'current_activity_status',
        'archived_at',
        'verified_at',
        'metadata',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    protected $hidden = [
        'password',
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeWithArchived($query)
    {
        return $query->withoutGlobalScope('active_students');
    }

    protected static function booted(): void
    {
        static::addGlobalScope('active_students', function ($query) {
            $query->where('status', 'active');
        });

        static::creating(function (self $student): void {
            if (empty($student->status)) {
                $student->status = 'active';
            }
        });
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_user_id');
    }

    public function trainingSessions()
    {
        return $this->hasMany(StudentTrainingSession::class, 'student_id');
    }

    public function latestTrainingSession()
    {
        return $this->hasOne(StudentTrainingSession::class, 'student_id')
            ->latest('started_at');
    }

    public function activityLogs()
    {
        return $this->hasMany(StudentActivityLog::class, 'student_id');
    }
}