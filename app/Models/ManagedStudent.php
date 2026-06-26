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
        'first_name',
        'middle_name',
        'last_name',
        'section',
        'current_progress',
        'archived_at',
        'verified_at',
        'metadata',
    ];

    protected $casts = [
        'archived_at' => 'datetime',
        'verified_at' => 'datetime',
        'current_progress' => 'array',
        'metadata' => 'array',
    ];

    protected $appends = [
        'full_name',
    ];

    protected $hidden = [
        'password',
    ];

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ])));
    }

    public function isModuleUnlocked(string $moduleKey): bool
    {
        if ($moduleKey === 'module-1') {
            return true;
        }

        $prevMap = [
            'module-2' => 'module-1',
            'module-3' => 'module-2',
            'module-4' => 'module-3',
        ];

        $prevModule = $prevMap[$moduleKey] ?? null;

        if (!$prevModule) {
            return false;
        }

        return $this->trainingSessions()
            ->where('module_key', $prevModule)
            ->where('status', 'completed')
            ->exists();
    }

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

    public function loginVerificationCodes()
    {
        return $this->hasMany(LoginVerificationCode::class, 'student_id');
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class, 'student_id');
    }
}
