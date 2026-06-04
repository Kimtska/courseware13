<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'student_profile_id',
        'recorded_by_user_id',
        'module_key',
        'score',
        'max_score',
        'recorded_at',
        'metadata',
    ];

    protected $casts = [
        'recorded_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function studentProfile()
    {
        return $this->belongsTo(StudentProfile::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by_user_id');
    }
}