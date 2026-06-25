<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentProfile extends Model
{
    use HasFactory;

    protected $fillable = [
        'instructor_id',
        'student_number',
        'school_name',
        'first_name',
        'middle_name',
        'last_name',
        'year_level',
        'section',
        'gender',
        'verification_status',
        'verified_by_user_id',
        'verified_at',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'verified_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by_user_id');
    }

    public function scores()
    {
        return $this->hasMany(StudentScore::class);
    }

    public function participationLogs()
    {
        return $this->hasMany(ModuleParticipationLog::class);
    }

    public function getFullNameAttribute(): string
    {
        return trim(implode(' ', array_filter([
            $this->first_name,
            $this->middle_name,
            $this->last_name,
        ])));
    }
}