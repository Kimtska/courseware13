<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'student_profile_id',
        'marked_by_user_id',
        'checked_in_at',
        'checked_out_at',
        'status',
        'metadata',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'checked_out_at' => 'datetime',
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

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by_user_id');
    }
}